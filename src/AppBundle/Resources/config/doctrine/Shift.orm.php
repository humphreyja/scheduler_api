<?php

use Doctrine\ORM\Mapping\ClassMetadataInfo;

$metadata->setInheritanceType(ClassMetadataInfo::INHERITANCE_TYPE_NONE);
$metadata->customRepositoryClassName = 'AppBundle\Repository\ShiftRepository';
$metadata->setChangeTrackingPolicy(ClassMetadataInfo::CHANGETRACKING_DEFERRED_IMPLICIT);
$metadata->mapField(array(
   'fieldName' => 'id',
   'type' => 'integer',
   'id' => true,
   'columnName' => 'id',
  ));
$metadata->mapField(array(
   'columnName' => 'manager_id',
   'fieldName' => 'managerId',
   'type' => 'integer',
  ));
$metadata->mapField(array(
   'columnName' => 'employee_id',
   'fieldName' => 'employeeId',
   'type' => 'integer',
   'nullable' => true,
  ));
$metadata->mapField(array(
   'columnName' => 'break',
   'fieldName' => 'break',
   'type' => 'float',
  ));
$metadata->mapField(array(
   'columnName' => 'start_time',
   'fieldName' => 'startTime',
   'type' => 'datetime',
  ));
$metadata->mapField(array(
   'columnName' => 'end_time',
   'fieldName' => 'endTime',
   'type' => 'datetime',
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