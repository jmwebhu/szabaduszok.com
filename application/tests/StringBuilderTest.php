<?php

class StringBuilderTest extends Unittest_TestCase
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