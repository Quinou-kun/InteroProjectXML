<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="xml" indent="yes" encoding="utf-8"/>
    <xsl:template match="/">
        <xsl:apply-templates select="previsions"/>
    </xsl:template>

    <xsl:template match="previsions">
        <xsl:copy>
            <xsl:copy-of select="@*"/>
            <echeance>
                <xsl:apply-templates select="echeance/temperature"/>
                <xsl:apply-templates select="echeance/pluie"/>
                <xsl:apply-templates select="echeance/humidite"/>
                <xsl:apply-templates select="echeance/vent_moyen"/>
                <xsl:apply-templates select="echeance/vent_rafales"/>
                <xsl:apply-templates select="echeance/vent_direction"/>
                <xsl:apply-templates select="echeance/risque_neige"/>
                <xsl:apply-templates select="echeance/nebulosite"/>
            </echeance>
        </xsl:copy>
    </xsl:template>

    <xsl:template match="echeance/temperature">
        <xsl:copy-of select="."/>
        <xsl:apply-templates />
    </xsl:template>
    <xsl:template match="echeance/pluie">
        <xsl:copy-of select="."/>
        <xsl:apply-templates />
    </xsl:template>
    <xsl:template match="echeance/humidite">
        <xsl:copy-of select="."/>
        <xsl:apply-templates />
    </xsl:template>
    <xsl:template match="echeance/vent_moyen">
        <xsl:copy-of select="."/>
        <xsl:apply-templates />
    </xsl:template>
    <xsl:template match="echeance/vent_rafales">
        <xsl:copy-of select="."/>
        <xsl:apply-templates />
    </xsl:template>
    <xsl:template match="echeance/vent_direction">
        <xsl:copy-of select="."/>
        <xsl:apply-templates />
    </xsl:template>
    <xsl:template match="echeance/risque_neige">
        <xsl:copy-of select="."/>
        <xsl:apply-templates />
    </xsl:template><xsl:template match="echeance/nebulosite">
    <xsl:copy-of select="."/>
    <xsl:apply-templates />
</xsl:template>


</xsl:stylesheet>