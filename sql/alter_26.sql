ALTER TABLE tenant ADD COLUMN ua_api_key TEXT;
ALTER TABLE tenant ADD COLUMN ua_api_secret TEXT;

UPDATE tenant SET ua_api_key='3ZdPxcFfSda0rpWtlwE68w',  ua_api_secret='42YO18MlSBC6JC-ewFoK2w';  --alloy

ALTER TABLE tenant ALTER COLUMN ua_api_key SET NOT NULL;
ALTER TABLE tenant ALTER COLUMN ua_api_secret SET NOT NULL;