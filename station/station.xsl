<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="xml" encoding="utf-8" indent="yes"/>
    <xsl:strip-space elements="previsions"/>
    <xsl:template match="/">
        <STATIONS>
            <xsl:apply-templates select="carto/markers"/>
        </STATIONS>
    </xsl:template>

    <xsl:template match="carto/markers">
       <xsl:copy-of select="."/>
    </xsl:template>
</xsl:stylesheet>