<?php
namespace Nap\Test\Serialisation;

class JSONTest extends \PHPUnit_Framework_TestCase
{
    /** @test **/
    public function serialisesDataToValidJSON()
    {
        // Arrange
        $data = array(
            "my_key" => "isMyValue"
        );

        $serialiser = new \Nap\Serialisation\JSON();

        // Act
        $actual = $serialiser->serialise($data);

        // Assert
        $this->assertJson($actual);
    }
} 