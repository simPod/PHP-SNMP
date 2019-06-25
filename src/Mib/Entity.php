<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Mib;

use SimPod\PhpSnmp\Transport\Snmp;

/**
 * See RFC 4133 https://tools.ietf.org/html/rfc4133
 */
class Entity
{
    public const OID_PHYSICAL_DESCRIPTION    = '.1.3.6.1.2.1.47.1.1.1.1.2';
    public const OID_PHYSICAL_VENDOR_TYPE    = '.1.3.6.1.2.1.47.1.1.1.1.3';
    public const OID_PHYSICAL_CONTAINED_IN   = '.1.3.6.1.2.1.47.1.1.1.1.4';
    public const OID_PHYSICAL_CLASS          = '.1.3.6.1.2.1.47.1.1.1.1.5';
    public const OID_PHYSICAL_PARENT_REL_POS = '.1.3.6.1.2.1.47.1.1.1.1.6';
    public const OID_PHYSICAL_NAME           = '.1.3.6.1.2.1.47.1.1.1.1.7';
    public const OID_PHYSICAL_HARDWARE_REV   = '.1.3.6.1.2.1.47.1.1.1.1.8';
    public const OID_PHYSICAL_FIRMWARE_REV   = '.1.3.6.1.2.1.47.1.1.1.1.9';
    public const OID_PHYSICAL_SOFTWARE_REV   = '.1.3.6.1.2.1.47.1.1.1.1.10';
    public const OID_PHYSICAL_SERIALNUM      = '.1.3.6.1.2.1.47.1.1.1.1.11';
    public const OID_PHYSICAL_SERIAL_NUM     = '.1.3.6.1.2.1.47.1.1.1.1.11';
    public const OID_PHYSICAL_MFG_NAME       = '.1.3.6.1.2.1.47.1.1.1.1.12';
    public const OID_PHYSICAL_MODEL_NAME     = '.1.3.6.1.2.1.47.1.1.1.1.13';
    public const OID_PHYSICAL_ALIAS          = '.1.3.6.1.2.1.47.1.1.1.1.14';
    public const OID_PHYSICAL_ASSET_ID       = '.1.3.6.1.2.1.47.1.1.1.1.15';
    public const OID_PHYSICAL_IS_FRU         = '.1.3.6.1.2.1.47.1.1.1.1.16';

    /**
     * @return iterable<string|int, string>
     */
    public function getPhysicalDescription(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_PHYSICAL_DESCRIPTION);
    }

    /**
     * @return iterable<string|int, string>
     */
    public function getPhysicalVendorType(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_PHYSICAL_VENDOR_TYPE);
    }

    /**
     * @return iterable<string|int, int>
     */
    public function getPhysicalContainedIn(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_PHYSICAL_CONTAINED_IN);
    }

    /**
     * @return iterable<string|int, int>
     */
    public function getPhysicalClass(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_PHYSICAL_CLASS);
    }

    /**
     * @return iterable<string|int, string>
     */
    public function getPhysicalName(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_PHYSICAL_NAME);
    }

    /**
     * @return iterable<string|int, string>
     */
    public function getPhysicalHardwareRev(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_PHYSICAL_HARDWARE_REV);
    }

    /**
     * @return iterable<string|int, string>
     */
    public function getPhysicalFirmwareRev(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_PHYSICAL_FIRMWARE_REV);
    }

    /**
     * @return iterable<string|int, string>
     */
    public function getPhysicalSoftwareRev(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_PHYSICAL_SOFTWARE_REV);
    }

    /**
     * @return iterable<string|int, string>
     */
    public function getPhysicalSerialNumber(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_PHYSICAL_SERIAL_NUM);
    }

    /**
     * @return iterable<string|int, string>
     */
    public function getPhysicalMfgName(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_PHYSICAL_MFG_NAME);
    }

    /**
     * @return iterable<string|int, string>
     */
    public function getPhysicalModelName(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_PHYSICAL_MODEL_NAME);
    }
}
