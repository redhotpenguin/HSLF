-- 
-- this is the twenty-first alter of the database.
-- dont forget to flush schema cache! Yii::app()->cache->flush();


-- ballot item news slug
ALTER TABLE ballot_item_news ADD COLUMN slug VARCHAR(512);

UPDATE ballot_item_news SET slug = LOWER( REPLACE( title ,' ', '-') ) ;

ALTER TABLE ballot_item_news ALTER COLUMN slug SET NOT NULL;


-- category field for alert_type
ALTER TABLE alert_type ADD COLUMN category VARCHAR(512);

UPDATE alert_type SET category = 'news';

ALTER TABLE alert_type ALTER COLUMN category SET NOT NULL;