<?php

namespace Module\Shared\ValueObject;

enum CompanyAttributesEnum
{
    case NAME;
    case ADDRESS;
    case EMAIL;
    case PHONE;
    case FAX;
    case INN;
    case OKED;
    case LOGO;
    case SIGNER;
    case STAMP_WITH_SIGN;
    case STAMP_WITHOUT_SIGN;
    case COUNTRY_AND_CITY;
}
