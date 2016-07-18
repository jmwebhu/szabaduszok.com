# language: hu

Jellemző: Bejelentkezés
    Mint egy regisztrált felhasználó
    Szeretnék bejelentkezni
    Hogy használni tudjam az alkalmazást

    @javascript
    Forgatókönyv: Bejelentkezés helyes adatokkal
        Amennyiben rákattintok a "login-anchor" menüpontra
        És kitöltöm az "email" mezőt a "joomartin@jmweb.hu" értékkel                
        És kitöltöm az "password" mezőt a "Deth4Life01" értékkel
        És beküldöm a "login-form" űrlapot
        Akkor látnom kell a címsorban a "Joó Martin" szöveget

    @javascript
    Forgatókönyv: Bejelentkezés helytelen adatokkal
        Amennyiben rákattintok a "login-anchor" menüpontra
        És kitöltöm az "email" mezőt a "joomartinasdf@jmweb.hu" értékkel                
        És kitöltöm az "password" mezőt a "Deth4Life01asdf" értékkel
        És beküldöm a "login-form" űrlapot
        Akkor látnom kell a "Hibás e-mail vagy jelszó. Kérjük próbáld meg újra!" hibaüzenetet