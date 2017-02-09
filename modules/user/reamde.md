#View hiearchia

##Profilok letrehozasa

Az alabbi linken talalhato egy kep, ami abarazolja a letrehozes view hiearchiat:
[Dropbox](https://www.dropbox.com/s/e6fa96ciu5pw7i7/User%20view%20hiearchia.jpg?dl=0)

###Fo template 

`views/User/write.twig`

**Blokkok**:

- `formHeader`: ebben van az oldal cime
- `personalProfile`: szemelyes adatok (nev, e-mail). Be van inclue -olva a `User/Write/baseContact.twig`
- `professionalProfile`: szakmai adatok  (iparagak, szakteruletek)
- `professionalProfileMisc`: ures blokk, ezt irja felul a szabaduszok template a kepessegekkel
- `externalProfiles`: ures blokk, ezt irja felul a szabaduszok template a kulso Profilokkal
- `address`: ures blokk, ezt irja felul a szabaduszok es a megbizo template a cimekkel (mivel mindketto mas)
- `credentials`: Jelszo
- `buttons`: mentes gomb

###Szabaduszo template

`views/User/Create/Freelancer/index.twig`

**Blokkkok**:

- `professionalProfileMisc`: fo template kiegeszitese egyeb adatokkal (kepessegekkel, Weboldal, stb)
- `externalProfiles`: fo template kiegeszitese kulso Profilokkal. inclue -olja a `User/Write/profiles.twig` 
- `address`: fo template kiegeszitese cimmel. inclue -olja a `User/Write/Freelancer/address.twig`

###Megbizo template

`views/Create/Employer/index.twig`

**Blokkok**:

- `personalProfile`: fo template kiegeszitese egyeb adatokkal. inclue -olja a `User/Write/company.twig` 
- `address`: fo template kiegeszitese cimmel

###Kiegeszito templatek

- `User/Write` mappaban levo templatek mindig inclue -olva vannak a fenti templatekben.

##Profilok megjelenitese

Az alabbi linken talalhato egy kep, ami abarazolja a megtekintes view hiearchiat:
[Dropbox](https://www.dropbox.com/s/ogk0xadapzs43rs/User%20profil%20megtekint%C3%A9s%20view%20hiearhia.jpg?dl=0)

###Fo template 

`views/User/profile.twig`

**Blokkok**:

- `profilePanel`: ures blokk. A gyerek templatek ezt irjak felul.

###Fo template alatti altalanos template

`views/User/parts/_profilePanel.twig`

A konkret Szabaduszo es Megbizo templatekben ez a template van include -olva es kiegeszitve `embed` hasznalataval

**Blokkok**:

- `imageContainer`: profilkep
- `detailsContainerContact`: kapcsolati informaciok (pl cim)
- `detailsContainerRelations`: iparagak, szakteruletek, kepessegek
- `ratingContainer`: ertekeles
- `descriptionContainer`: rovid szakmai bemutatkozas
- `bottomContainer`: ures blokk. Itt lesznek a profil alatti egyeb dolgok (pl.: projektek)

###Szabaduszo template

`views/User/Profile/Freelancer/index.twig`

**Blokkok**:

- `profilePanel`: embed -eli a `views/User/parts/_profilePanel.twig` templatet, es felulirja benn a szukseges dolgokat
- `imageContainer`: kep, es a kulso profilok
- `detailsContainerContact`: alap infok es oraber, weboldal, cv
- `detailsContainerRelations`: alap iparagak, szakteruletek, kiegeszitve kepessegekkel
- `bottomContainer`: alap container, kiegeszitve projekt ertesitovel, es azokkal a projektekkel, 
amikben resztvesz, vagy jelentkezett rajuk. Itt be van include -olva ket masik template:
`User/parts/Profile/Freelancer/_candidateList.twig` es `User/parts/Profile/Freelancer/_participantList.twig`. Az elso azokat a projekteket listazza, amikre jelentkezett, a masodik pedig amikben resztvesz.

###Megbizo template

`views/User/Profile/Employer/index.twig`

**Blokkok**:

- `profilePanel`: embed -eli a `views/User/parts/_profilePanel.twig` templatet, es felulirja benn a szukseges dolgokat
- `detailsContainerContact`: alap infok es cegnec, Telefonszam
- `bottomContainer`: a Megbizo altal letrehozott projektek. Be van include -olva a `Project/parts/_list.twig` template
