<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="UTF-8" indent="yes"/>

    <xsl:template match="/">
        <html>
            <head><title>Консоли построчно</title></head>
            <body>
                <h2>Список консолей</h2>
                <xsl:apply-templates select="consoles/console"/>
            </body>
        </html>
    </xsl:template>

    <xsl:template match="console">
        <div style="margin-bottom: 15px;">
            <strong><xsl:value-of select="name"/></strong> —
            <xsl:choose>
                <xsl:when test="@type='Home'">Домашняя</xsl:when>
                <xsl:when test="@type='Handheld'">Портативная</xsl:when>
                <xsl:when test="@type='Hybrid'">Гибридная</xsl:when>
            </xsl:choose>,
            <xsl:value-of select="release"/> г. —
            Продано: <xsl:value-of select="sales"/> млн.
        </div>
    </xsl:template>
</xsl:stylesheet>
