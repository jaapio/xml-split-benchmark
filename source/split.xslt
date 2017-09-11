<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:output method="xml" indent="yes"/>

    <xsl:template match="d2LogicalModel">
        <xsl:apply-templates/>
    </xsl:template>

    <xsl:template match="payloadPublication">
        <xsl:for-each-group select="siteMeasurements" group-starting-with="siteMeasurements[(position() -1)mod 3 = 0]">
            <xsl:variable name="file" select="concat('siteMeasurements',position(),'.xml')"/>
            <xsl:result-document href="{$file}">
                <DATA>
                    <DATASET>
                        <xsl:copy-of select="current-group()"/>
                    </DATASET>
                </DATA>
            </xsl:result-document>
        </xsl:for-each-group>
    </xsl:template>

</xsl:stylesheet>
