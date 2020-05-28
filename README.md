# phpTest
Adatbázis listázás és manipuláció PHP-val
Szállítási listákat generál aktív státuszu megrendelésekből, raktárkészletet figyelmen kívül hagyva.


MariaDB adatbázishoz csatlakozik.
Jelszó tárolás nem megfelelő (egyszerűség kedvéért plaintext), WAMP szerverkonfiguráció karakterkódolást sokszor "eltöri".

***
```
Adatbázis felépítése:

//AI: Auto Increment
//PK: Primary Key

raktar:
  
  //Megrendelők
  #costumers
    ID: int(11) - AI - PK
    name: varchar(50)
    addr: varchar(75)
    birthd: date
    contact: varchar(20)
  
  //Szállítási fejléc
  #deliveryhead
    deliveryID: int(11) - AI - PK
    status: varchar(3)
    truckID: varchar(6)
  
  //Szállítás párosítása megrendeléssel
  #deliverybody 
    deliveryID: int(11) - PK
    orderID: int(11) - PK
  
  //Személyzet szállításhoz rendelése
  #deliveryback  
    deliveryID: int(11) - PK
    personelID: int(11) - PK
  
  //Egyedi megrendelés fejléc
  #orderheaders
    ID: int(11) - AI - PK
    customerID: int(11)
    date: date
    addr: varchar(75)
    status: varchar(3) - def:"act"
    
  //Megrendelések tartalma
  #orderbody
    orderID: int(11) - PK
    productID: int(11) - PK
    quantity: int(11)

  //Személyzet
  #personel
    ID: int(11) - AI - PK
    name: varchar(50)
    status: char(1) //"a" vagy "p"  (aktív vagy passzív)
  
  //Raktárkészlet
  #stock
    ID: int(11) - AI - PK
    name: varchar(50)
    quantity: int(11)
    location: varchar(10)
    weight: int(11)
   
  //Kaminflotta
  #trucks
    plateID: varchar(6) - PK
    type: varchar(30)
    capacityKG: int(11)
    capacityPerson: int(1)
    status: varchar(3)
    
  //Felhasználók táblája    
  #users
    ID: int(11) - AI - PK
    name: varchar(32)
    user_name: varchar(16)
    password: varchar(32)
```
***
