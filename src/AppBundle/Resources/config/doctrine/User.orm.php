<?php

use Doctrine\ORM\Mapping\ClassMetadataInfo;

$metadata->setInheritanceType(ClassMetadataInfo::INHERITANCE_TYPE_NONE);
$metadata->customRepositoryClassName = 'AppBundle\Repository\UserRepository';
$metadata->setChangeTrackingPolicy(ClassMetadataInfo::CHANGETRACKING_DEFERRED_IMPLICIT);
$metadata->mapField(array(
   'fieldName' => 'id',
   'type' => 'integer',
   'id' => true,
   'columnName' => 'id',
  ));
$metadata->mapField(array(
   'columnName' => 'name',
   'fieldName' => 'name',
   'type' => 'string',
   'length' => 255,
   'nullable' => true,
  ));
$metadata->mapField(array(
   'columnName' => 'role',
   'fieldName' => 'role',
   'type' => 'string',
   'length' => 255,
  ));
$metadata->mapField(array(
   'columnName' => 'email',
   'fieldName' => 'email',
   'type' => 'string',
   'length' => 255,
   'nullable' => true,
  ));
$metadata->mapField(array(
   'columnName' => 'phone',
   'fieldName' => 'phone',
   'type' => 'string',
   'length' => 255,
   'nullable' => true,
  ));
$metadata->mapField(array(
   'columnName' => 'created_at',
   'fieldName' => 'createdAt',
   'type' => 'datetime',
  ));
$metadata->mapField(array(
   'columnName' => 'updated_at',
   'fieldName' => 'updatedAt',
   'type' => 'datetime',
  ));
$metadata->setIdGeneratorType(ClassMetadataInfo::GENERATOR_TYPE_AUTO);