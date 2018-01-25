<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="xml" indent="yes" encoding="utf-8"/>
    <xsl:template match="/">
        <GEO>
            <xsl:apply-templates/>
        </GEO>
    </xsl:template>

    <xsl:template match="query">
        <lat><xsl:value-of select="lat"/></lat>
        <lon><xsl:value-of select="lon"/></lon>
    </xsl:template>
</xsl:stylesheet>