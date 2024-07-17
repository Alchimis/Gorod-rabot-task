SELECT article, dealer, price FROM shop
WHERE price = (
	SELECT MAX(price) FROM shop as shop1
    WHERE shop1.article = shop.article
);