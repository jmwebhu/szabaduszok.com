<?php

class StringBuilder_Test extends Unittest_TestCase
{
    /**
     * @covers StringBuilder::append
     */
    public function testAppend()
    {
        $sb = new StringBuilder('elso');                
        $this->assertEquals($sb->get(), 'elso');
        
        $sb->append('-masodik');
        $this->assertEquals($sb->get(), 'elso-masodik');
        
        $sb->append(',.');
        $this->assertEquals($sb->get(), 'elso-masodik,.');
        
        $sb->append(' űáéúőóüöí');
        $this->assertEquals($sb->get(), 'elso-masodik,. űáéúőóüöí');
        
        $sb->append('!%@|');
        $this->assertEquals($sb->get(), 'elso-masodik,. űáéúőóüöí!%@|');              
    }

    /**
     * @covers StringBuilder::append
     */
    public function testAppendNull()
    {
        $sb = new StringBuilder('elso');
        $sb->append(null);

        $this->assertEquals('elso', $sb->get());

        $sb->append('');
        $this->assertEquals('elso', $sb->get());

        $sb = new StringBuilder();
        $sb->append(null);

        $this->assertEquals('', $sb->get());

        $sb->append('');
        $this->assertEquals('', $sb->get());
    }
    
    /**
     * @covers StringBuilder::get
     */
    public function testGet()
    {
        $sb = new StringBuilder();                
        
        $this->assertNull($sb->get());
        $this->assertEquals($sb->get('default'), 'default');
               
        $sb->append('string');
        $this->assertNotEquals($sb->get('default'), 'default');
    }
}