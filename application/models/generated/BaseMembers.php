<?php

/**
 * BaseMembers
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string $email
 * @property timestamp $created_date
 * @property timestamp $last_login
 * @property integer $status
 * @property string $tel
 * @property string $mobile
 * @property Doctrine_Collection $Contents
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseMembers extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('members');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('username', 'string', 45, array(
             'type' => 'string',
             'length' => '45',
             ));
        $this->hasColumn('password', 'string', 125, array(
             'type' => 'string',
             'length' => '125',
             ));
        $this->hasColumn('name', 'string', 45, array(
             'type' => 'string',
             'length' => '45',
             ));
        $this->hasColumn('email', 'string', 125, array(
             'type' => 'string',
             'length' => '125',
             ));
        $this->hasColumn('created_date', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('last_login', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('status', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('tel', 'string', 45, array(
             'type' => 'string',
             'length' => '45',
             ));
        $this->hasColumn('address', 'string', 125, array(
             'type' => 'string',
             'length' => '125',
             ));
        $this->hasColumn('mobile', 'string', 45, array(
             'type' => 'string',
             'length' => '45',
             ));     

        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
        $this->option('type', 'MyISAM');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Contents', array(
             'local' => 'id',
             'foreign' => 'members_id'));
    }
}