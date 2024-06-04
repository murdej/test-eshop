# Eshop app

Použil jsem Symfony + Doctrine. Mapování a schéma řeší doctrine a odpovídá struktuře php s entit.

Alternativ je více. U svého projektu bych použil Nette a své rozšíření pro Nette database.

Trochu jsem se zasekl na DQL ale nic co by nevyřešilo 5 minut googlení.

## Entity

### Značka `Brand`

### Kategorie `Category`

Kategorie produktů. Kategorie je možnné strukturovat do stromu pomocí `parentCategory`

### Číselník typu parametrů `ParamType`

### Číselník hodnot parametrů `ParamType`

Hodnota parametru. 

 - Je napojena k typu parametru n:1.
 - K jiným entitám se napojuje jako n:m
 - Existuje vždy jen jeden záznam pro hodnotu a typ parametru bez ohledu na to u kolika produktů, nebo jiných entit, se používá

#### Možnosti rozšíření

Pro překlady by se přidala entita `ParamTypeTranslate` která by obsahovala odkaz na `ParamType` a `Language` a hodnotu v danám jazyce.

### Produkt `Product`

#### Možnosti rozšíření

 - Cena je teď jen jako sloupec. Pokud by byly potřeba různé ceny, byla by cena uložena v entitě `ProductPrice` která by odkazovala na `Product`, uživatelskou skupinu, datumčas od - do, nebo jiné podmínky.
 - Pokud by se odkaz na kategorii změnil na n:m může být produkt umístěný ve více kategoriích

### Článek blogu `BlogPost`

Napojení na parametry stejně jako u product.

## Filtrování podle parametrů

### Backend

Základ filtrování jsem udělal v `ProductRepository`.`getByFilter`. Metoda přijímá id typu parametru a seznam id hodnot, nebo rozsah hodnot v případě číselného parametru.

Pro každý filtrovaný parametr se přidá JOIN hodnoty parametru a ve WHERE se hodnota porovná.
**Alternativa** je použít WHERE EXISTS a CTE.

### Frontend

Formulář filtru by měl mít sadu checkbodů pro číselníkové parametry a range inputy pro číselné paramtery

Načítání by po změně filtru mělo načítat produkty asynchronně pomocí fetch a následně bez reloadu změnit parametry v url.

Prvotní načtení stránky by ale mělo udělat rendering na serveru pro zaazení do vyhledávačů.

## Nastavení parametrů v administraci

Ukládání parametrů jsem udělal v `ProductManager`.`setProductParamValues`. Metoda přijímá produkt a pole ve formátu `[ id_typu_parametru => hodnota ]`.

Metoda porovná data uložená v DB s predanými daty a upraví/přidá/odebere záznamy podle toho co je potřeba. V případě potřeby vytvoří nové `ParamValue`.