<?php
namespace Javiern\DAO;

class UserProfile extends BaseDAO
{
    public function getUserProfile($id)
    {
        $sql = "SELECT * FROM user_profile WHERE id = :id LIMIT 1";

        $stmt = $this->getDb()->prepare($sql);
        $stmt->bindValue("id", $id);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function newUserProfile($profile)
    {
        $name    = array_key_exists('name',    $profile) ? $profile['name']    : null;
        $address = array_key_exists('address', $profile) ? $profile['address'] : null;

        $this->getDb()->insert(
            "user_profile",
            [
                'name' => $name,
                'address' => $address
            ]
        );

        return $this->getDb()->lastInsertId();
    }

    public function saveUserProfile($profile)
    {
        $id      = array_key_exists('id',      $profile) ? $profile['id']    : null;
        $name    = array_key_exists('name',    $profile) ? $profile['name']    : null;
        $address = array_key_exists('address', $profile) ? $profile['address'] : null;

        return $this->getDb()->update(
            "user_profile",
            [
                'id'      => $id,
                'name'    => $name,
                'address' => $address
            ],
            [
                'id' => $id
            ]
        );
    }

    public function removeUserProfile($id)
    {
        return $this->getDb()->delete(
            "user_profile",
            ['id' => $id]
        );
    }
}