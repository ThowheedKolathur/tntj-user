<?php
/**
 * Created by PhpStorm.
 * User: E.FARID
 * Date: 07-Oct-19
 * Time: 6:32 AM
 */
namespace App\Model\Entity;
use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;
class Users extends Entity {
    public function _setPassword($password) {
        $hasher = new DefaultPasswordHasher();
        return $hasher->hash($password);
    }
}