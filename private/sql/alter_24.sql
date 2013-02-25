--- rename ballot item to item
ALTER TABLE ballot_item RENAME TO item;


--- rename ballot item news to item new
ALTER TABLE ballot_item_news RENAME TO item_news;


-- rename news.ballot_item_id to news.item_id
ALTER TABLE item_news RENAME COLUMN ballot_item_id TO item_id;


--- rename endorser_ballot_item to endorser_item
ALTER TABLE endorser_ballot_item RENAME TO endorser_item;

-- rename endorser_item.ballot_item_id to endorser_item.item_id
ALTER TABLE endorser_item RENAME COLUMN ballot_item_id TO item_id;


-- rename scorecard.ballot_item_id to scorecard.item_id
ALTER TABLE scorecard RENAME COLUMN ballot_item_id TO item_id;