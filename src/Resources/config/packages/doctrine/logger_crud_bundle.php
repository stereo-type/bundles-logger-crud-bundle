<?php

/**
 * @package    logger_crud_bundle.php
 * @copyright  2024 Zhalayletdinov Vyacheslav evil_tut@mail.ru
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

declare(strict_types=1);

return [
    'AcademCity\LoggerCrudBundle' => [
        'type'      => 'attribute',
        'is_bundle' => false,
        'dir'       => '%kernel.project_dir%/vendor/academcity/logger-crud-bundle/src/Domain/Entity',
        'prefix'    => 'AcademCity\LoggerCrudBundle\Domain\Entity',
        'alias'     => 'AcademCity\LoggerCrudBundle',
    ],
];
