<?php

class BusinessProjectTest extends Unittest_TestCase
{
    /**
     * @var ORM
     */
    private $_model;

    /**
     * @covers Business_Project::getSearchTextFromFields()
     */
    public function testGetSearchTextFromFieldstWithoutRelations()
    {
        $this->givenModelWithTestData();

        $business = new Business_Project($this->_model);
        $actual = $business->getSearchTextFromFields();

        $expected = 'Folyamatos webfejlesztések Lelkes, megbízható, kiegyensúlyozott webprogramozót keresek projektek, folyamatos web fejlesztések elvégzésére. Először kisebb otthonról végezhető munkák lennének, és ha együtt tudunk működni, akkor több weboldalam fejlesztésében részt vehetsz. Diáknak, kezdő programozónak is nyitott az állás. Elvárások: HTML CSS referencia munkák angol tudás határidők betartása Előnyt jelent: kereső marketing tudás (SEO) fizetési módok webshopba integrálás ismerete kreativitás, és önképzés (nem baj ha kezdő vagy, de a felmerülő feladatokat képes legyél önállóan megoldani) Projektek amikben részt vehetsz: parajdisokincsek.hu webfejlesztés semmiszor.hu webfejlesztés http://semmiszor.hu/stilettodress/ webfejlesztés Jelentkezés: Fényképes önéletrajzodat, és motivációs leveledet várom, a szkladanyi.attila@parajdisokincsek.hu email címre. A motivációs levélben kitérhetsz a fenti 3 oldallal kapcsolatban milyen fejlesztési, egyéb ötleteid lennének. joomartin@jmweb.hu 06301923380 ' . date('Y-m-d') . '    ';

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Business_Project::getSearchTextFromFields()
     */
    public function testGetSearchTextFromFieldsWithRelations()
    {
        $this->givenMockWithTestData();

        $business = new Business_Project($this->_mock);
        $actual = $business->getSearchTextFromFields();

        $expected = 'Folyamatos webfejlesztések Lelkes, megbízható, kiegyensúlyozott webprogramozót keresek projektek, folyamatos web fejlesztések elvégzésére. Először kisebb otthonról végezhető munkák lennének, és ha együtt tudunk működni, akkor több weboldalam fejlesztésében részt vehetsz. Diáknak, kezdő programozónak is nyitott az állás. Elvárások: HTML CSS referencia munkák angol tudás határidők betartása Előnyt jelent: kereső marketing tudás (SEO) fizetési módok webshopba integrálás ismerete kreativitás, és önképzés (nem baj ha kezdő vagy, de a felmerülő feladatokat képes legyél önállóan megoldani) Projektek amikben részt vehetsz: parajdisokincsek.hu webfejlesztés semmiszor.hu webfejlesztés http://semmiszor.hu/stilettodress/ webfejlesztés Jelentkezés: Fényképes önéletrajzodat, és motivációs leveledet várom, a szkladanyi.attila@parajdisokincsek.hu email címre. A motivációs levélben kitérhetsz a fenti 3 oldallal kapcsolatban milyen fejlesztési, egyéb ötleteid lennének. joomartin@jmweb.hu 06301923380 ' . date('Y-m-d') . ' industries professions skills ';
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Business_Project::getShortDescriptionCutOffAt()
     */
    public function testGetShortDescriptionCutOffAtTooLong()
    {
        $this->givenModelWithTestData();

        $business = new Business_Project($this->_model);
        $actual = $business->getShortDescriptionCutOffAt();

        $expected = 'Lelkes, megbízható, kiegyensúlyozott webprogramozót keresek projektek, folyamatos web fejlesztések e...';

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Business_Project::getShortDescriptionCutOffAt()
     */
    public function testGetShortDescriptionCutOffAtStandardLength()
    {
        $this->givenModelWithTestData();
        $this->_model->short_description = 'Lelkes, megbízható webprogramozót keresek';

        $business = new Business_Project($this->_model);
        $actual = $business->getShortDescriptionCutOffAt();

        $expected = 'Lelkes, megbízható webprogramozót keresek';

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Business_Project::getShortDescriptionCutOffAt()
     */
    public function testGetShortDescriptionCutOffAtUnique()
    {
        $this->givenModelWithTestData();

        $business = new Business_Project($this->_model);
        $actual = $business->getShortDescriptionCutOffAt(20);

        $expected = 'Lelkes, megbízható, ...';

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Business_Project::getNameCutOffAt()
     */
    public function testGetNameCutOffAtTooLong()
    {
        $this->givenModelWithTestData();
        $this->_model->name = 'Folyamatos webfejlesztések Folyamatos webfejlesztések Folyamatos webfejlesztések';

        $business = new Business_Project($this->_model);
        $actual = $business->getNameCutOffAt();

        $expected = 'Folyamatos webfejlesztések Folyamatos webfejlesztések Folyamatos webfe...';

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Business_Project::getNameShortenedBy()
     */
    public function testGetNameCutOffAtNormalLong()
    {
        $this->givenModelWithTestData();

        $business = new Business_Project($this->_model);
        $actual = $business->getNameCutOffAt();

        $expected = 'Folyamatos webfejlesztések';

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Business_Project::getNameCutOffAt()
     */
    public function testGetNameCutOffAtUniqe()
    {
        $this->givenModelWithTestData();
        $this->_model->name = 'Folyamatos webfejlesztések Folyamatos webfejlesztések Folyamatos webfejlesztések';

        $business = new Business_Project($this->_model);
        $actual = $business->getNameCutOffAt(20);

        $expected = 'Folyamatos webfejles...';

        $this->assertEquals($expected, $actual);
    }

    protected function givenModelWithTestData()
    {
        $model = new Model_Project();
        $model->name = 'Folyamatos webfejlesztések';
        $model->short_description = $this->getShortDescriptionForSearch();
        $model->long_description = $this->getLongDescriptionForSearch();

        $model->email = 'joomartin@jmweb.hu';
        $model->phonenumber = '06301923380';

        $this->_model = $model;
    }

    protected function givenMockWithTestData()
    {
        $mock = new Model_ProjectMock();

        $mock->name = 'Folyamatos webfejlesztések';
        $mock->short_description = $this->getShortDescriptionForSearch();
        $mock->long_description = $this->getLongDescriptionForSearch();

        $mock->user = new Model_User();
        $mock->email = 'joomartin@jmweb.hu';
        $mock->phonenumber = '06301923380';

        $this->_mock = $mock;
    }

    protected function getShortDescriptionForSearch()
    {
        return 'Lelkes, megbízható, kiegyensúlyozott webprogramozót keresek projektek, folyamatos web fejlesztések elvégzésére. Először kisebb otthonról végezhető munkák lennének, és ha együtt tudunk működni, akkor több weboldalam fejlesztésében részt vehetsz. Diáknak, kezdő programozónak is nyitott az állás.';
    }

    protected function getLongDescriptionForSearch()
    {
        return 'Elvárások: HTML CSS referencia munkák angol tudás határidők betartása Előnyt jelent: kereső marketing tudás (SEO) fizetési módok webshopba integrálás ismerete kreativitás, és önképzés (nem baj ha kezdő vagy, de a felmerülő feladatokat képes legyél önállóan megoldani) Projektek amikben részt vehetsz: parajdisokincsek.hu webfejlesztés semmiszor.hu webfejlesztés http://semmiszor.hu/stilettodress/ webfejlesztés Jelentkezés: Fényképes önéletrajzodat, és motivációs leveledet várom, a szkladanyi.attila@parajdisokincsek.hu email címre. A motivációs levélben kitérhetsz a fenti 3 oldallal kapcsolatban milyen fejlesztési, egyéb ötleteid lennének.';
    }
}