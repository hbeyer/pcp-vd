<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xs="http://www.w3.org/2001/XMLSchema"
    xmlns:marc="http://www.loc.gov/MARC21/slim"
    exclude-result-prefixes="xs"
    version="1.0">

    <xsl:output method="xml" indent="yes" encoding="utf-8" />
    
    <xsl:template match="/">
        
        <recordList>
            <xsl:for-each select="marc:record">
            <record>
            <xsl:for-each select="marc:datafield[@tag='035']/marc:subfield[@code='a']">
                <xsl:if test="substring(self::*, 1, 3) = 'VD1'">
                    <relation><xsl:value-of select="self::*"/></relation>
                </xsl:if>
            </xsl:for-each>
            <xsl:for-each select="marc:datafield[@tag='041']/marc:subfield[@code='a']">
                <language><xsl:value-of select="self::*"/></language>
            </xsl:for-each>
            <xsl:for-each select="marc:datafield[@tag='245']/marc:subfield[@code='a']">
                <title><xsl:value-of select="self::*"/></title>
            </xsl:for-each>
            <xsl:for-each select="marc:datafield[@tag='246']/marc:subfield[@code='a']">
                <title><xsl:value-of select="self::*"/></title>
            </xsl:for-each>
            <xsl:for-each select="marc:datafield[@tag='100']">
                <creator>
                    <xsl:value-of select="marc:subfield[@code='a']"/><xsl:for-each select="marc:subfield[@code='0']"><xsl:if test="substring(self::*, 2, 6) = 'DE-588'">#<xsl:value-of select="concat('http://d-nb.info/gnd/', substring(self::*, 9))"/></xsl:if></xsl:for-each>
                </creator>
            </xsl:for-each>

            <!-- Contributor und publisher -->
            <xsl:for-each select="marc:datafield[@tag='700']">
                <xsl:choose>
                    <xsl:when test="marc:subfield[@code='4']/text() = 'prt'">
                       <publisher><xsl:value-of select="marc:subfield[@code='a']"/></publisher>
                    </xsl:when>
                    <xsl:otherwise>
                    <contributor><xsl:value-of select="marc:subfield[@code='a']"/><xsl:for-each select="marc:subfield[@code='0']"><xsl:if test="substring(self::*, 2, 6) = 'DE-588'">#<xsl:value-of select="concat('http://d-nb.info/gnd/', substring(self::*, 9))"/></xsl:if></xsl:for-each></contributor>
                    </xsl:otherwise>
                </xsl:choose>
            </xsl:for-each>

            <!-- Datensätze ohne normierten Drucker abfangen -->
            <xsl:choose>
                <xsl:when test="marc:datafield[@tag='700']/marc:subfield[@code='4']/text() = 'prt'"><!-- nichts tun --></xsl:when>
                <xsl:otherwise>
                    <xsl:for-each select="marc:datafield[@tag='264']/marc:subfield[@code='b']">
                        <publisher><xsl:value-of select="self::*"/></publisher>
                    </xsl:for-each>
                </xsl:otherwise>
            </xsl:choose>
            
            <!-- Erscheinungsort -->
            <xsl:for-each select="marc:datafield[@tag='751']">
                <xsl:if test="marc:subfield[@code='4']/text() = 'pup' or 'prp' or 'mfp' or 'dbp'">
                <place><xsl:value-of select="marc:subfield[@code='a']"/></place>
                </xsl:if>
            </xsl:for-each>

            <!-- Datensätze ohne normierten Erscheinungsort abfangen -->
            <xsl:choose>
                <xsl:when test="marc:datafield[@tag='751']/marc:subfield[@code='4']/text() = 'pup' or 'prp' or 'mfp' or 'dbp'"><!-- nichts tun --></xsl:when>
                <xsl:otherwise>
                    <xsl:for-each select="marc:datafield[@tag='264']/marc:subfield[@code='a']">
                        <place><xsl:value-of select="self::*"/></place>
                    </xsl:for-each>
                </xsl:otherwise>
            </xsl:choose>

            <!-- Erscheinungsjahr -->
            <xsl:if test="marc:datafield[@tag='264']/marc:subfield[@code ='c']">
                <date><xsl:value-of select="marc:datafield[@tag='264']/marc:subfield[@code ='c']"/></date>
            </xsl:if>

            <xsl:for-each select="marc:datafield[@tag='300']/marc:subfield[@code='a']">
                <pages><xsl:value-of select="self::*"/></pages>
            </xsl:for-each>
            <xsl:for-each select="marc:datafield[@tag='300']/marc:subfield[@code='c']">
                <format><xsl:value-of select="self::*"/></format>
            </xsl:for-each>
            <xsl:for-each select="marc:datafield[@tag='655']/marc:subfield[@code='a']">
                <subject><xsl:value-of select="self::*"/></subject>
            </xsl:for-each>            
            <xsl:for-each select="marc:datafield[@tag='856']/marc:subfield[@code='u']">
                <hasFormat><xsl:value-of select="self::*"/></hasFormat>
            </xsl:for-each>
            </record>
        </xsl:for-each>
        </recordList>
        
    </xsl:template>
    
</xsl:stylesheet>
