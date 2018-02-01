<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" media-type="text/html" encoding="utf-8" />
    <xsl:strip-space elements="previsions"/>
    <xsl:template match="/">
        <DIV class="meteo">
            <TABLE style="width : 100%">
                <CAPTION>
                    <h1>
                        Meteo du jour
                    </h1>
                </CAPTION>
                <TR>
                    <TH></TH>
                    <TH>Temperature</TH>
                    <TH>Humidité de l'air</TH>
                    <TH>Vent</TH>
                    <TH>Temps</TH>
                </TR>
                <xsl:apply-templates select="previsions/echeance"/>
            </TABLE>
        </DIV>
    </xsl:template>

    <xsl:template match="previsions/echeance">
        <xsl:if test="@hour &gt; 2 and @hour &lt; 25">
            <TR id="meteo_body">
                <TH><xsl:apply-templates select="@timestamp"/></TH>
                <TD><xsl:apply-templates select="./temperature/level"/></TD>
                <TD><xsl:apply-templates select="./humidite"/></TD>
                <TD><xsl:apply-templates select="./vent_moyen"/></TD>
                <TD>
                    <xsl:apply-templates select="./risque_neige"/>
                </TD>
            </TR>
        </xsl:if>
    </xsl:template>

    <xsl:template match="@timestamp">
        <xsl:value-of select="substring(.,12,5)"/>
    </xsl:template>

    <xsl:template match="echeance/temperature/level">
        <xsl:if test="@val = 'sol'">
            <xsl:value-of select="concat(round(. -273.15),'°C')"/>
        </xsl:if>
    </xsl:template>

    <xsl:template match="echeance/humidite">
        <xsl:value-of select="concat(.,' %')"/>
    </xsl:template>

    <xsl:template match="echeance/vent_moyen">
        <xsl:value-of select="concat(., ' km/h')"/>
    </xsl:template>

    <xsl:template match="echeance/risque_neige">
        <xsl:choose>
            <xsl:when test=". = 'oui'">
                <img src="../Interoperabilite/img/meteo/neige.png" class="meteo_img"/>
            </xsl:when>
            <xsl:otherwise>
                <xsl:variable name="conseil">
                <h2>Risque de neige, privilégiez la marche !</h2>
            </xsl:variable>
                <xsl:apply-templates select="../pluie"/>
            </xsl:otherwise>
        </xsl:choose>

    </xsl:template>

    <xsl:template match="echeance/pluie">
        <xsl:if test=". &gt; 0.5">
            <img src="../Interoperabilite/img/meteo/pluie_moderee.png" class="meteo_img"/>
        </xsl:if>
        <xsl:if test=". &gt; 0 and . &lt; 0.5">
            <img src="../Interoperabilite/img/meteo/pluie_legere.png" class="meteo_img"/>

        </xsl:if>
        <xsl:if test=". = 0">
            <xsl:apply-templates select="../nebulosite/level[@val = 'totale']"/>
        </xsl:if>
    </xsl:template>

    <xsl:template match="echeance/nebulosite/level[@val = 'totale']">
        <xsl:if test=". = 0">
            <img src="../Interoperabilite/img/meteo/soleil.png" class="meteo_img"/>
        </xsl:if>
        <xsl:if test=". &gt; 0 and . &lt; 50">
            <img src="../Interoperabilite/img/meteo/parseme.png" class="meteo_img"/>
        </xsl:if>
        <xsl:if test=". &gt; 51 and . &lt; 101">
            <img src="../Interoperabilite/img/meteo/couvert.png" class="meteo_img"/>
        </xsl:if>
    </xsl:template>
</xsl:stylesheet>