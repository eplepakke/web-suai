<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="UTF-8" indent="yes"/>

    <xsl:template match="/">
        <html>
            <head>
                <title>Продажи консолей Nintendo</title>
                <style>
                    table { border-collapse: collapse; width: 100%; }
                    th, td { border: 1px solid #333; padding: 8px; text-align: center; }
                </style>
            </head>
            <body>
                <h2>Таблица продаж Nintendo</h2>
                <table>
                    <tr>
                        <th>Название</th>
                        <th>Тип</th>
                        <th>Год</th>
                        <th>Продано (млн)</th>
                        <th>Изображение</th>
                    </tr>
                    <xsl:apply-templates select="consoles/console">
                        <xsl:sort select="sales" data-type="number" order="descending"/>
                    </xsl:apply-templates>
                </table>
            </body>
        </html>
    </xsl:template>

    <xsl:template match="console">
        <tr>
            <td><xsl:value-of select="name"/></td>
            <td>
                <xsl:choose>
                    <xsl:when test="@type='Home'">Домашняя</xsl:when>
                    <xsl:when test="@type='Handheld'">Портативная</xsl:when>
                    <xsl:when test="@type='Hybrid'">Гибрид</xsl:when>
                </xsl:choose>
            </td>
            <td><xsl:value-of select="release"/></td>
            <td><xsl:value-of select="sales"/></td>
            <td>
                <xsl:if test="image">
                    <img>
                        <xsl:attribute name="src"><xsl:value-of select="image"/></xsl:attribute>
                        <xsl:attribute name="width">100</xsl:attribute>
                        <xsl:attribute name="alt"><xsl:value-of select="name"/></xsl:attribute>
                    </img>
                </xsl:if>
            </td>
        </tr>
    </xsl:template>
</xsl:stylesheet>
