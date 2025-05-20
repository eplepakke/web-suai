<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:output method="html" encoding="UTF-8" indent="yes" />
    <xsl:key name="games-by-console" match="Game" use="console_id" />

    <xsl:template match="/">
        <html>
            <body>
                <h1>Список игровых консолей и их игр</h1>

                <xsl:for-each select="DataSet/Console">
                    <div>
                        <h2>
                            <xsl:value-of select="name" />
                        </h2>
                        <p><strong>Год выпуска:</strong> <xsl:value-of select="release_year" /> |
                            <strong>Поколение:</strong> <xsl:value-of select="generation" /> |
                            <strong>Снята с производства:</strong>
                            <xsl:choose>
                                <xsl:when test="discontinued='true'">Да</xsl:when>
                                <xsl:otherwise>Нет</xsl:otherwise>
                            </xsl:choose>
                        </p>

                        <xsl:variable name="consoleId" select="id" />
                        <xsl:if test="key('games-by-console', $consoleId)">
                            <table>
                                <tr>
                                    <th>Название</th>
                                    <th>Жанр</th>
                                    <th>Дата выхода</th>
                                    <th>Разработчик</th>
                                </tr>
                                <xsl:for-each select="key('games-by-console', $consoleId)">
                                    <tr>
                                        <td><xsl:value-of select="title" /></td>
                                        <td><xsl:value-of select="genre" /></td>
                                        <td><xsl:value-of select="release_date" /></td>
                                        <td><xsl:value-of select="developer" /></td>
                                    </tr>
                                </xsl:for-each>
                            </table>
                        </xsl:if>

                        <xsl:if test="not(key('games-by-console', $consoleId))">
                            <p><em>Нет доступных игр для этой консоли.</em></p>
                        </xsl:if>
                    </div>
                </xsl:for-each>
            </body>
        </html>
    </xsl:template>

</xsl:stylesheet>
