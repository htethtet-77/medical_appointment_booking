<?php
namespace Asus\Medical\helpers;

use Asus\Medical\libraries\Database;

class OIDCUserHelper
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * $provider: string like 'google'
     * $claims: array with keys 'sub','email','email_verified','name','picture',...
     * returns user_id (int) on success or false on failure
     */
    public function handleOIDCLogin(string $provider, array $claims)
    {
        // 1) Look up by provider+subject (most reliable)
        $identity = $this->db->findIdentity($provider, $claims['sub'] ?? '');

        if (!empty($identity)) {
            return (int)$identity['user_id'];
        }

        // 2) No identity found: try to find user by email (if provided)
        $email = $claims['email'] ?? null;
        $email_verified = !empty($claims['email_verified']) ? 1 : 0;

        $user = null;
        if ($email) {
            $user = $this->db->columnFilter('users', 'email', $email);
        }

        // 3) If no user, create one
        if (empty($user)) {
            // prepare user data (set sensible defaults)
            $userData = [
                'name'         => $claims['name'] ?? 'Unknown',
                'email'        => $email,
                'password'     => null,                 // no password for OIDC accounts
                'type_id'      => ROLE_PATIENT,
                'status_id'    => 6,
                'is_active'    => 1,
                'is_confirmed' => $email_verified ? 1 : 0,
                'profile_image'=> $claims['picture'] ?? 'default_profile.jpg'
            ];

            $userId = $this->db->create('users', $userData);

            if (!$userId) {
                return false;
            }
        } else {
            $userId = (int)$user['id'];

            // Optionally update user flags if email_verified is true and was previously 0
            if ($email_verified && empty($user['is_confirmed'])) {
                $this->db->update('users', $userId, ['is_confirmed' => 1]);
            }

            // Optionally update profile picture if empty
            if (!empty($claims['picture']) && empty($user['profile_image'])) {
                $this->db->update('users', $userId, ['profile_image' => $claims['picture']]);
            }
        }

        // 4) Create user_identities record linking provider+subject -> user
        $identityData = [
            'user_id'       => $userId,
            'provider'      => $provider,
            'subject'       => $claims['sub'] ?? '',
            'email'         => $email,
            'email_verified'=> $email_verified,
            'picture'       => $claims['picture'] ?? null,
            'raw_claims'    => json_encode($claims),
        ];

        $this->db->create('user_identities', $identityData);

        return (int)$userId;
    }
}
