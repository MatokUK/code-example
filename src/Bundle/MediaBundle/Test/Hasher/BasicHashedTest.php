<?php
namespace Matok\Bundle\MediaBundle\Test\Hasher;

use PHPUnit\Framework\TestCase;
use Matok\Bundle\MediaBundle\Hasher\BasicHasher;

class BasicHashedTest extends TestCase
{
    /**
     * @dataProvider getFilenames
     */
    public function testHashFilenamePreserveExtension($filename)
    {
        $extension = $this->getExtension($filename);
        $hasher = new BasicHasher();
        $hash = $hasher->hash($filename);
        $hashExtension = substr($hash, - strlen($extension));

        $this->assertEquals($extension, $hashExtension);
    }

    public function getFilenames()
    {
        return array(
            array('test_a.jpg'),
            array('test_b.JPG'),
            array('test_c.png.JPG'),
            array('test'),
        );
    }

    /**
     * @dataProvider getFilenameForExtensionTest
     */
    public function testRemoveExtension($filenameWithExtension, $filenameWithoutExtension)
    {
        $hasher = new BasicHasher();
        $result = $hasher->removeExtension($filenameWithExtension);

        $this->assertEquals($filenameWithoutExtension, $result);
    }

    public function getFilenameForExtensionTest()
    {
        return array(
            array('test_a.jpg', 'test_a'),
            array('test_b.JPG', 'test_b'),
            array('test_c.png.JPG', 'test_c.png'),
            array('test', 'test'),
        );
    }

    private function getExtension($filename)
    {
        $parts = explode('.', $filename);
        if (count($parts) > 1) {
            return strtolower(end($parts));
        }
    }
}