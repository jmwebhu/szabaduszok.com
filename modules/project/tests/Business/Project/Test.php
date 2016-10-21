<?php

class Business_Project_Test extends Unittest_TestCase
{
    /**
     * @var ORM
     */
    private $_model;

    /**
     * @covers Business_Project::getSearchTextFromFields()
     */
    public function testGetSearchTextFromFieldstWithoutRelationsWithoutUser()
    {
        $this->givenModelWithTestData();

        $business = new Business_Project($this->_model);
        $actual = $business->getSearchTextFromFields();

        $expected = 'Folyamatos webfejlesztések Lelkes, megbízható, kiegyensúlyozott webprogramozót keresek projektek, folyamatos web fejlesztések elvégzésére. Először kisebb otthonról végezhető munkák lennének, és ha együtt tudunk működni, akkor több weboldalam fejlesztésében részt vehetsz. Diáknak, kezdő programozónak is nyitott az állás. Elvárások: HTML CSS referencia munkák angol tudás határidők betartása Előnyt jelent: kereső marketing tudás (SEO) fizetési módok webshopba integrálás ismerete kreativitás, és önképzés (nem baj ha kezdő vagy, de a felmerülő feladatokat képes legyél önállóan megoldani) Projektek amikben részt vehetsz: parajdisokincsek.hu webfejlesztés semmiszor.hu webfejlesztés http://semmiszor.hu/stilettodress/ webfejlesztés Jelentkezés: Fényképes önéletrajzodat, és motivációs leveledet várom, a szkladanyi.attila@parajdisokincsek.hu email címre. A motivációs levélben kitérhetsz a fenti 3 oldallal kapcsolatban milyen fejlesztési, egyéb ötleteid lennének. joomartin@jmweb.hu 06301923380 ' . date('Y-m-d') . '       ';

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

        $expected = 'Folyamatos webfejlesztések Lelkes, megbízható, kiegyensúlyozott webprogramozót keresek projektek, folyamatos web fejlesztések elvégzésére. Először kisebb otthonról végezhető munkák lennének, és ha együtt tudunk működni, akkor több weboldalam fejlesztésében részt vehetsz. Diáknak, kezdő programozónak is nyitott az állás. Elvárások: HTML CSS referencia munkák angol tudás határidők betartása Előnyt jelent: kereső marketing tudás (SEO) fizetési módok webshopba integrálás ismerete kreativitás, és önképzés (nem baj ha kezdő vagy, de a felmerülő feladatokat képes legyél önállóan megoldani) Projektek amikben részt vehetsz: parajdisokincsek.hu webfejlesztés semmiszor.hu webfejlesztés http://semmiszor.hu/stilettodress/ webfejlesztés Jelentkezés: Fényképes önéletrajzodat, és motivációs leveledet várom, a szkladanyi.attila@parajdisokincsek.hu email címre. A motivációs levélben kitérhetsz a fenti 3 oldallal kapcsolatban milyen fejlesztési, egyéb ötleteid lennének. joomartin@jmweb.hu 06301923380 ' . date('Y-m-d') . '     industries professions skills';
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Business_Project::getFieldCutOffAt()
     */
    public function testGetFieldCutOffAtShortDescriptionTooLong()
    {
        $this->givenModelWithTestData();

        $business = new Business_Project($this->_model);
        $actual = $business->getFieldCutOffAt('short_description');

        $expected = 'Lelkes, megbízható, kiegyensúlyozott webprogramozót keresek projektek, foly...';

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Business_Project::getFieldCutOffAt()
     */
    public function testGetFieldCutOffAtShortDescriptionNormalLength()
    {
        $this->givenModelWithTestData();
        $this->_model->short_description = 'Lelkes, megbízható webprogramozót keresek';

        $business = new Business_Project($this->_model);
        $actual = $business->getFieldCutOffAt('short_description');

        $expected = 'Lelkes, megbízható webprogramozót keresek';

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Business_Project::getFieldCutOffAt()
     */
    public function testGetFieldCutOffAtShortDescriptionUnique()
    {
        $this->givenModelWithTestData();

        $business = new Business_Project($this->_model);
        $actual = $business->getFieldCutOffAt('short_description', 20);

        $expected = 'Lelkes, megbízható, ...';

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Business_Project::getFieldCutOffAt()
     */
    public function testGetFieldCutOffAtNameTooLong()
    {
        $this->givenModelWithTestData();
        $this->_model->name = 'Folyamatos webfejlesztések Folyamatos webfejlesztések Folyamatos webfejlesztések';

        $business = new Business_Project($this->_model);
        $actual = $business->getFieldCutOffAt('name');

        $expected = 'Folyamatos webfejlesztések Folyamatos webfejlesztések Folyamatos webfejlesz...';

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Business_Project::getFieldCutOffAt()
     */
    public function testGetFieldCutOffAtNameNormalLong()
    {
        $this->givenModelWithTestData();

        $business = new Business_Project($this->_model);
        $actual = $business->getFieldCutOffAt('name');

        $expected = 'Folyamatos webfejlesztések';

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Business_Project::getFieldCutOffAt()
     */
    public function testGetFieldCutOffAtUniqe()
    {
        $this->givenModelWithTestData();
        $this->_model->name = 'Folyamatos webfejlesztések Folyamatos webfejlesztések Folyamatos webfejlesztések';

        $business = new Business_Project($this->_model);
        $actual = $business->getFieldCutOffAt('name', 20);

        $expected = 'Folyamatos webfejles...';

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Business_Project::getRelationIdField()
     */
    public function testGetRelationIdField()
    {
        $industry = new Model_Project_Industry();
        $pk = Business_Project::getRelationIdField($industry);

        $this->assertEquals('industry_id', $pk);

        $profession = new Model_Project_Profession();
        $pk = Business_Project::getRelationIdField($profession);

        $this->assertEquals('profession_id', $pk);

        $skill = new Model_Project_Skill();
        $pk = Business_Project::getRelationIdField($skill);

        $this->assertEquals('skill_id', $pk);
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