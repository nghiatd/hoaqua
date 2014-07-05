<?php

/**
 * BaseSettings
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $id
 * @property string $name
 * @property clob $value
 * @property enum $type
 * @property integer $section
 * @property string $desc
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7691 2011-02-04 15:43:29Z jwage $
 */
abstract class BaseSettings extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('settings');
        $this->hasColumn('id', 'string', 256, array(
             'type' => 'string',
             'primary' => true,
             'length' => '256',
             ));
        $this->hasColumn('name', 'string', 45, array(
             'type' => 'string',
             'length' => '45',
             ));
        $this->hasColumn('value', 'clob', 65535, array(
             'type' => 'clob',
             'length' => '65535',
             ));
        $this->hasColumn('type', 'enum', null, array(
             'type' => 'enum',
             'values' => 
             array(
              0 => 'text',
              1 => 'textarea',
              2 => 'tinymce',
             ),
             'default' => 'text',
             ));
        $this->hasColumn('section', 'integer', 1, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => '1',
             ));
        $this->hasColumn('desc', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));

        $this->option('charset', 'utf8');
        $this->option('type', 'MyISAM');
        $this->option('collate', 'utf8_general_ci');
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}