<?php

namespace App\Sql;

/**
 * Тут хранятся SQL крипты, что бы не перегружать код
 * Class SqlScripts
 */
class SqlScripts
{
    /**
     * Поиск партии с цветом
     * (тяжелый запрос, выполняется долго, примерно 4-6 секунд)
     * @return string
     */
    public static function getSqlQueryWithColor(): string
    {
        return $sql = <<<sql
            SET NOCOUNT ON;
            DECLARE      @InventBatchId NVARCHAR(10),
             @Partition BIGINT,
             @DataAreaId NVARCHAR(5),
             @ProdInventBatchId NVARCHAR(20);
            /* Маски партий для теста
            * N'21%107903'
            * N'RLC%61595'
            * N'21%19471'
            * N'21%16867'
            */
            SET @InventBatchId = :number; --подставить номер партии
            SET @Partition = 5637144576;
            SET @DataAreaId = 'rlc';

            IF OBJECT_ID('tempdb.dbo.#Initial') IS NOT NULL
                DROP TABLE #Initial;

            SELECT INVENTDIM.INVENTBATCHID as Batch,
                CAST((COALESCE(INVENTTABLE_PT.NAMEALIAS,INVENTTABLE.NAMEALIAS)) as nvarchar(100)) as NAMEALIAS,
                CAST((COALESCE(INVENTDIM_PT.RUK_InventConfigId,INVENTDIM.RUK_InventConfigId)) as nvarchar(50)) as CONFIGID,
                CAST((COALESCE(INVENTDIM_PT.RUK_INVENTCOLORID,INVENTDIM.RUK_INVENTCOLORID)) as nvarchar(100)) as COLORID,
                CAST(INVENTDIM.WMSLOCATIONID as nvarchar(50)) as WMSLOCATION,
                CAST(INVENTDIM.LICENSEPLATEID as nvarchar(50)) as LICENSE,
                ROW_NUMBER() over(partition by INVENTDIM.INVENTBATCHID order by INVENTDIM.INVENTBATCHID) as rn

            INTO #Initial
            FROM INVENTSUM INVENTSUM WITH (READUNCOMMITTED)
            LEFT JOIN INVENTDIM INVENTDIM ON INVENTDIM.INVENTDIMID = INVENTSUM.INVENTDIMID
            JOIN INVENTTABLE INVENTTABLE ON INVENTSUM.ITEMID = INVENTTABLE.ITEMID
            LEFT JOIN ProdTable ProdTable ON INVENTDIM.INVENTBATCHID = ProdTable.ProdID
            LEFT JOIN INVENTDIM INVENTDIM_PT ON INVENTDIM_PT.INVENTDIMID = ProdTable.INVENTDIMID
            LEFT JOIN INVENTTABLE INVENTTABLE_PT ON ProdTable.ITEMID = INVENTTABLE_PT.ITEMID
            WHERE	INVENTSUM.PARTITION = @Partition AND INVENTSUM.DATAAREAID = @DataAreaId
                AND INVENTDIM.PARTITION = @Partition AND INVENTDIM.DATAAREAID = @DataAreaId
                AND INVENTDIM.PARTITION = @Partition AND INVENTDIM.DATAAREAID = @DataAreaId
                AND INVENTSUM.PHYSICALINVENT != 0
                AND INVENTSUM.CLOSEDQTY = 0
                AND INVENTSUM.CLOSED = 0
                AND INVENTDIM.INVENTBATCHID LIKE @InventBatchId

            /* Обработка слияния нескольких разных строк (цвет, ячейка, номерной знак) в
             * разрезе партий и артикулов (замена агрегатным
             * функциям, которые доступны в SQL Server 2017 (14.x))
            */
            ;WITH RecursiveConcate
            AS (
                SELECT Batch
                    ,CAST(NAMEALIAS AS NVARCHAR(100)) AS NAMEALIAS
                    ,CAST(CONFIGID AS NVARCHAR(max)) AS CONFIGID
                    ,CAST(COLORID AS NVARCHAR(max)) AS COLORID
                    ,CAST(WMSLOCATION AS NVARCHAR(max)) AS WMSLOCATION
                    ,CAST(LICENSE AS NVARCHAR(max)) AS LICENSE
                    ,2 [rn]
                FROM #Initial AS Initt
                WHERE Initt.rn = 1

                UNION ALL

                SELECT Initt.batch
                    ,Initt.NAMEALIAS
                    ,IIF(RecCon.CONFIGID LIKE '%' + Initt.CONFIGID + '%', RecCon.CONFIGID, RecCon.CONFIGID + ', ' + Initt.CONFIGID)
                    ,IIF(RecCon.COLORID LIKE '%' + Initt.COLORID + '%', RecCon.COLORID, RecCon.COLORID + ', ' + Initt.COLORID)
                    ,IIF(RecCon.WMSLOCATION LIKE '%' + Initt.WMSLOCATION + '%', RecCon.WMSLOCATION, RecCon.WMSLOCATION + ', ' + Initt.WMSLOCATION)
                    ,IIF(RecCon.LICENSE LIKE '%' + Initt.LICENSE + '%', RecCon.LICENSE, RecCon.LICENSE + ', ' + Initt.LICENSE)
                    ,RecCon.rn + 1
                FROM #Initial AS Initt
                JOIN RecursiveConcate RecCon ON Initt.rn = RecCon.rn
                    AND Initt.Batch = RecCon.batch
                )
                ,mRank
            AS (
                SELECT Batch
                    ,NAMEALIAS
                    ,CONFIGID
                    ,COLORID
                    ,WMSLOCATION
                    ,LICENSE
                    ,MAX(rn) OVER (PARTITION BY batch) AS mrn
                    ,rn
                FROM RecursiveConcate
                )
            SELECT  BATCH
                    ,NAMEALIAS
                    ,REPLACE(CONFIGID , ' , ', '') as CONFIGID
                    ,REPLACE(COLORID , ' , ', '') as COLORID
                    ,REPLACE(WMSLOCATION , ' , ', '') as WMSLOCATION
                    ,REPLACE(LICENSE, ' , ', '') as LICENSE
            FROM mRank
            WHERE Batch IN (SELECT DISTINCT Batch FROM RecursiveConcate)
                  AND rn IN (mrn)
            OPTION (MAXRECURSION 32767)
            DROP TABLE #Initial
        sql;
    }

    /**
     * Поиск партий без поиска цвета
     * (быстрый запрос, примерно 1-3 секунды)
     * @return string
     */
    public static function getSqlQuery(): string
    {
        return $sql = <<<sql
            SET NOCOUNT ON;
        IF OBJECT_ID('tempdb.dbo.#Initial') IS NOT NULL DROP TABLE #Initial;

        SELECT INVENTDIM.INVENTBATCHID as Batch,
              CAST((COALESCE(INVENTTABLE_PT.NAMEALIAS,INVENTTABLE.NAMEALIAS)) as nvarchar(100)) as NAMEALIAS,
              CAST(INVENTDIM.WMSLOCATIONID as nvarchar(50)) as WMSLOCATION,
              CAST(INVENTDIM.LICENSEPLATEID as nvarchar(50)) as LICENSE,
              ROW_NUMBER() over(partition by INVENTDIM.INVENTBATCHID order by INVENTDIM.INVENTBATCHID) as rn
            INTO #Initial
            FROM INVENTSUM INVENTSUM WITH (READUNCOMMITTED)
            LEFT JOIN INVENTDIM INVENTDIM ON INVENTDIM.INVENTDIMID = INVENTSUM.INVENTDIMID
            JOIN INVENTTABLE INVENTTABLE ON INVENTSUM.ITEMID = INVENTTABLE.ITEMID
            LEFT JOIN ProdTable ProdTable ON INVENTDIM.INVENTBATCHID = ProdTable.ProdID
            LEFT JOIN INVENTTABLE INVENTTABLE_PT ON ProdTable.ITEMID = INVENTTABLE_PT.ITEMID
        WHERE INVENTSUM.PARTITION = 5637144576 AND INVENTSUM.DATAAREAID = 'rlc'
             AND INVENTDIM.PARTITION = 5637144576 AND INVENTDIM.DATAAREAID = 'rlc'
             AND INVENTSUM.PHYSICALINVENT != 0
             AND INVENTSUM.CLOSEDQTY = 0
             AND INVENTSUM.CLOSED = 0
             AND INVENTDIM.INVENTBATCHID LIKE :number

        ;WITH RecursiveConcate
        AS (
            SELECT Batch
                ,CAST(NAMEALIAS AS NVARCHAR(100)) AS NAMEALIAS
                ,CAST(WMSLOCATION AS NVARCHAR(max)) AS WMSLOCATION
                ,CAST(LICENSE AS NVARCHAR(max)) AS LICENSE
                ,2 [rn]
            FROM #Initial AS Initt
            WHERE Initt.rn = 1

            UNION ALL

            SELECT Initt.batch
                ,Initt.NAMEALIAS
                ,IIF(RecCon.WMSLOCATION LIKE '%' + Initt.WMSLOCATION + '%', RecCon.WMSLOCATION, RecCon.WMSLOCATION + ', ' + Initt.WMSLOCATION)
                ,IIF(RecCon.LICENSE LIKE '%' + Initt.LICENSE + '%', RecCon.LICENSE, RecCon.LICENSE + ', ' + Initt.LICENSE)
                ,RecCon.rn + 1
            FROM #Initial AS Initt
            JOIN RecursiveConcate RecCon ON Initt.rn = RecCon.rn
                AND Initt.Batch = RecCon.batch
            )
            ,mRank
        AS (
            SELECT Batch
                ,NAMEALIAS
                ,WMSLOCATION
                ,LICENSE
                ,MAX(rn) OVER (PARTITION BY batch) AS mrn
                ,rn
            FROM RecursiveConcate
            )
        SELECT  BATCH
                ,NAMEALIAS
                ,REPLACE(WMSLOCATION , ' , ', '') as WMSLOCATION
                ,REPLACE(LICENSE, ' , ', '') as LICENSE
        FROM mRank
        WHERE Batch IN (SELECT DISTINCT Batch FROM RecursiveConcate)
              AND rn IN (mrn)
        OPTION (MAXRECURSION 32767)
        DROP TABLE #Initial
        sql;
    }

    /**
     * Get sql query from find by partial number
     * @return string
     */
    public static function getSqlQueryByPartial(): string
    {
        return $sql = <<<sql
            SET NOCOUNT ON;
            DECLARE      @locationId NVARCHAR(10);

            SET @locationId = :number;

            IF OBJECT_ID('tempdb.dbo.#Initial') IS NOT NULL
                DROP TABLE #Initial;

            SELECT INVENTDIM.INVENTBATCHID as Batch,
              CAST((COALESCE(INVENTTABLE_PT.NAMEALIAS,INVENTTABLE.NAMEALIAS)) as nvarchar(max)) as NAMEALIAS,
              CAST(INVENTDIM.WMSLOCATIONID as nvarchar(max)) as WMSLOCATION,
              CAST(INVENTDIM.LICENSEPLATEID as nvarchar(max)) as LICENSE,
              ROW_NUMBER() over(partition by INVENTDIM.INVENTBATCHID order by INVENTDIM.INVENTBATCHID) as rn
            INTO #Initial
            FROM INVENTSUM INVENTSUM WITH (READUNCOMMITTED)
            LEFT LOOP JOIN INVENTDIM INVENTDIM ON INVENTDIM.INVENTDIMID = INVENTSUM.INVENTDIMID
            JOIN INVENTTABLE INVENTTABLE ON INVENTSUM.ITEMID = INVENTTABLE.ITEMID
            LEFT JOIN ProdTable ProdTable ON INVENTDIM.INVENTBATCHID = ProdTable.ProdID
            LEFT JOIN INVENTTABLE INVENTTABLE_PT ON ProdTable.ITEMID = INVENTTABLE_PT.ITEMID
                   WHERE  INVENTSUM.PARTITION = 5637144576 AND INVENTSUM.DATAAREAID = 'rlc'
                         AND INVENTDIM.PARTITION = 5637144576 AND INVENTDIM.DATAAREAID = 'rlc'
                         AND INVENTSUM.PHYSICALINVENT != 0
                         AND INVENTSUM.CLOSEDQTY = 0
                         AND INVENTSUM.CLOSED = 0
                         AND INVENTDIM.WMSLOCATIONID LIKE @locationId

            ;WITH RecursiveConcate
            AS (
                SELECT Batch
                    ,CAST(NAMEALIAS AS NVARCHAR(max)) AS NAMEALIAS
                    ,CAST(WMSLOCATION AS NVARCHAR(max)) AS WMSLOCATION
                    ,CAST(LICENSE AS NVARCHAR(max)) AS LICENSE
                    ,2 [rn]
                FROM #Initial AS Initt
                WHERE Initt.rn = 1

                UNION ALL

                SELECT Initt.batch
                    ,Initt.NAMEALIAS
                    ,IIF(RecCon.WMSLOCATION LIKE '%' + Initt.WMSLOCATION + '%', RecCon.WMSLOCATION, RecCon.WMSLOCATION + ', ' + Initt.WMSLOCATION)
                    ,IIF(RecCon.LICENSE LIKE '%' + Initt.LICENSE + '%', RecCon.LICENSE, RecCon.LICENSE + ', ' + Initt.LICENSE)
                    ,RecCon.rn + 1
                FROM #Initial AS Initt
                JOIN RecursiveConcate RecCon ON Initt.rn = RecCon.rn
                    AND Initt.Batch = RecCon.batch
                )
                ,mRank
            AS (
                SELECT Batch
                    ,NAMEALIAS
                    ,WMSLOCATION
                    ,LICENSE
                    ,MAX(rn) OVER (PARTITION BY batch) AS mrn
                    ,rn
                FROM RecursiveConcate
                )
            SELECT  BATCH
                    ,NAMEALIAS
                    ,REPLACE(WMSLOCATION , ' , ', '') as WMSLOCATION
                    ,REPLACE(LICENSE, ' , ', '') as LICENSE
            FROM mRank
            WHERE Batch IN (SELECT DISTINCT Batch FROM RecursiveConcate)
                  AND rn IN (mrn)
            OPTION (MAXRECURSION 32767)
            DROP TABLE #Initial
        sql;
    }

    /**
     * Get sql query from find by partial number with users
     * @return string
     */
    public static function getSqlQueryByPartialWithUsers(): string
    {
        return $sql = <<<sql
            SET NOCOUNT ON;
            DECLARE @InventBatchId NVARCHAR(10)
            ,@Partition BIGINT
            ,@DataAreaId NVARCHAR(5)
            ,@ProdInventBatchId NVARCHAR(20);

        /* Маски партий для теста
        * 21%0110783
        * 21%20410
        * 19%0006054
        * 21%0127140
        * 19%0004117
        * 20%0066201
        * 20%0066182
        */
        SET @InventBatchId = :number;--подставить номер партии
        SET @Partition = 5637144576;
        SET @DataAreaId = 'rlc';

        IF OBJECT_ID('tempdb.dbo.#Initial') IS NOT NULL
            DROP TABLE #Initial;

        WITH PreInitial as (
            SELECT INVENTDIM.INVENTBATCHID AS Batch,
            CAST((COALESCE(INVENTTABLE_PT.NAMEALIAS, INVENTTABLE.NAMEALIAS)) AS NVARCHAR(100)) AS NAMEALIAS,
            CAST((COALESCE(INVENTDIM_PT.RUK_INVENTCOLORID, INVENTDIM.RUK_INVENTCOLORID)) AS NVARCHAR(100)) AS COLORID,
            CAST(INVENTDIM.WMSLOCATIONID AS NVARCHAR(50)) AS WMSLOCATION,
            CAST(INVENTDIM.LICENSEPLATEID AS NVARCHAR(50)) AS LICENSE,
            COALESCE(WWU.USERNAME, '') AS USERNAME,
            IT.DATEPHYSICAL,
            MAX(IT.DatePhysical) OVER (PARTITION BY  INVENTDIM.INVENTBATCHID,INVENTDIM.WMSLOCATIONID) as MaxDate
            FROM INVENTSUM INVENTSUM WITH (READUNCOMMITTED)

            LEFT JOIN INVENTDIM INVENTDIM ON INVENTDIM.INVENTDIMID = INVENTSUM.INVENTDIMID
            JOIN INVENTTABLE INVENTTABLE ON INVENTSUM.ITEMID = INVENTTABLE.ITEMID
            LEFT JOIN ProdTable ProdTable ON INVENTDIM.INVENTBATCHID = ProdTable.ProdID
            LEFT JOIN INVENTDIM INVENTDIM_PT ON INVENTDIM_PT.INVENTDIMID = ProdTable.INVENTDIMID
            LEFT JOIN INVENTTABLE INVENTTABLE_PT ON ProdTable.ITEMID = INVENTTABLE_PT.ITEMID
            LEFT JOIN INVENTTRANS IT ON IT.INVENTDIMID = INVENTSUM.INVENTDIMID AND IT.STATUSRECEIPT IN (1, 2)
            LEFT JOIN INVENTTRANSORIGIN ITO ON ITO.RECID = IT.INVENTTRANSORIGIN
            LEFT JOIN WHSWORKLINE WWL ON WWL.WORKID = ITO.REFERENCEID
            LEFT JOIN WHSWORKUSER WWU ON WWU.USERID = WWL.USERID

            WHERE   ITO.REFERENCECATEGORY = 201
            AND INVENTSUM.PARTITION = @Partition AND INVENTSUM.DATAAREAID = @DataAreaId
            AND INVENTDIM.PARTITION = @Partition AND INVENTDIM.DATAAREAID = @DataAreaId
            AND INVENTDIM_PT.PARTITION = @Partition AND INVENTDIM_PT.DATAAREAID = @DataAreaId
            AND INVENTSUM.PHYSICALINVENT != 0
            AND INVENTSUM.CLOSEDQTY = 0
            AND INVENTSUM.CLOSED = 0
            AND INVENTDIM.INVENTBATCHID LIKE @InventBatchId
        )
        SELECT *,ROW_NUMBER() OVER (PARTITION BY BATCH ORDER BY BATCH) AS rn
        INTO #Initial
        FROM PreInitial
        WHERE DATEPHYSICAL = MaxDate

        /* Обработка слияния нескольких разных строк (цвет, ячейка, номерной знак) в
        * разрезе партий и артикулов (замена агрегатным
        * функциям, которые доступны в SQL Server 2017 (14.x))
        */

        ;WITH RecursiveConcate
        AS (
            SELECT Batch
                ,CAST(NAMEALIAS AS NVARCHAR(100)) AS NAMEALIAS
                ,CAST(COLORID AS NVARCHAR(max)) AS COLORID
                ,CAST(WMSLOCATION AS NVARCHAR(max)) AS WMSLOCATION
                ,CAST(LICENSE AS NVARCHAR(max)) AS LICENSE
                ,CAST(USERNAME AS NVARCHAR(max)) AS USERNAME
                ,2 [rn]
            FROM #Initial AS Initt
            WHERE Initt.rn = 1

            UNION ALL

            SELECT Initt.batch
                ,Initt.NAMEALIAS
                ,IIF(RecCon.COLORID LIKE '%' + Initt.COLORID + '%', RecCon.COLORID, RecCon.COLORID + ', ' + Initt.COLORID)
                ,IIF(RecCon.WMSLOCATION LIKE '%' + Initt.WMSLOCATION + '%', RecCon.WMSLOCATION, RecCon.WMSLOCATION + ', ' + Initt.WMSLOCATION)
                ,IIF(RecCon.LICENSE LIKE '%' + Initt.LICENSE + '%', RecCon.LICENSE, RecCon.LICENSE + ', ' + Initt.LICENSE)
                ,IIF(RecCon.USERNAME LIKE '%' + Initt.USERNAME + '%', RecCon.USERNAME, RecCon.USERNAME + ', ' + Initt.USERNAME)
                ,RecCon.rn + 1
            FROM #Initial AS Initt
            JOIN RecursiveConcate RecCon ON Initt.rn = RecCon.rn
                AND Initt.Batch = RecCon.batch
            )
            ,mRank
        AS (
            SELECT Batch
                ,NAMEALIAS
                ,COLORID
                ,WMSLOCATION
                ,LICENSE
                ,USERNAME
                ,MAX(rn) OVER (PARTITION BY batch) AS mrn
                ,rn
            FROM RecursiveConcate
            )
        SELECT BATCH
            ,NAMEALIAS
            ,REPLACE(COLORID, ' , ', '') AS COLORID
            ,REPLACE(WMSLOCATION, ' , ', '') AS WMSLOCATION
            ,REPLACE(LICENSE, ' , ', '') AS LICENSE
            ,REPLACE(USERNAME, ' , ', '') AS USERNAME
        FROM mRank
        WHERE Batch IN (
                SELECT DISTINCT Batch
                FROM RecursiveConcate
                )
            AND rn IN (mrn)
        OPTION (MAXRECURSION 0)

        DROP TABLE #Initial
        sql;
    }
}
