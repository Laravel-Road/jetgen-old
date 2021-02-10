<?php

return [
    'blueprint_types' => [
        'increments', 'integerIncrements', 'tinyIncrements', 'smallIncrements', 'mediumIncrements', 'bigIncrements',
        'char', 'string', 'text', 'mediumText', 'longText',
        'integer', 'tinyInteger', 'smallInteger', 'mediumInteger', 'bigInteger',
        'unsignedInteger', 'unsignedTinyInteger', 'unsignedSmallInteger', 'unsignedMediumInteger', 'unsignedBigInteger',
        'float', 'double', 'decimal', 'unsignedDecimal',
        'boolean',
        'enum', 'set',
        'json', 'jsonb',
        'date', 'dateTime', 'dateTimeTz',
        'time', 'timeTz', 'timestamp', 'timestampTz', 'timestamps',
        'timestamps', 'timestampsTz', 'softDeletes', 'softDeletesTz',
        'year',
        'binary',
        'uuid',
        'ipAddress',
        'macAddress',
        'geometry', 'point', 'lineString', 'polygon', 'geometryCollection', 'multiPoint', 'multiLineString', 'multiPolygon', 'multiPolygonZ',
        'computed',
        'morphs', 'nullableMorphs', 'uuidMorphs', 'nullableUuidMorphs',
        'rememberToken',
        'foreign', 'foreignId', 'foreignIdFor', 'foreignUuid',
    ],
];
