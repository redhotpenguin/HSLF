-- 
-- this is the twenty-second alter of the database.
-- dont forget to flush schema cache! Yii::app()->cache->flush();

-- endorser: share text
ALTER TABLE endorser ADD COLUMN facebook_share VARCHAR(1024);
ALTER TABLE endorser ADD COLUMN twitter_share VARCHAR(140);
