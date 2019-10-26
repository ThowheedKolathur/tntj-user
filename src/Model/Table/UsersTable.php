<?php
/**
 * Created by PhpStorm.
 * User: E.FARID
 * Date: 28-Sep-19
 * Time: 3:40 AM
 */
namespace App\Table;
use Cake\ORM\Table;

class UsersTable extends Table {
    public function initialize(array $config)
    {
        parent::initialize($config); // TODO: Change the autogenerated stub
        //Behaviour
        $this->addBehavior('Timestamp');

        //Association
        $this->belongsTo('Roles',[
            'className' => 'Roles',
            'foreignKey' => 'role_id'
        ]);
    }
}