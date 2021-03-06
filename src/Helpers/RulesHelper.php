<?php

namespace Recruitment\Helpers;

class RulesHelper
{
    public const PROPERTY_FIND = 'findProducts';
    public const PROPERTY_MATCH = 'matchProducts';
    public const PROPERTY_NAME = 'name';

    public const PROPERTIES = [
        self::PROPERTY_FIND,
        self::PROPERTY_MATCH,
        self::PROPERTY_NAME,
    ];

    public const SUBPROPERTY_EQUALS = 'equals';
    public const SUBPROPERTY_PARAMETER = 'parameter';

    public const SUBPROPERTIES = [
        self::SUBPROPERTY_EQUALS,
        self::SUBPROPERTY_PARAMETER,
    ];

    public const RESERVED_KEYWORD_ANY = 'any';
    public const RESERVED_KEYWORD_IS_EMPTY = 'is empty';
    public const RESERVED_KEYWORD_THIS = 'this';

    public const RESERVED_KEYWORDS = [
        self::RESERVED_KEYWORD_ANY,
        self::RESERVED_KEYWORD_IS_EMPTY,
        self::RESERVED_KEYWORD_THIS,
    ];
}
