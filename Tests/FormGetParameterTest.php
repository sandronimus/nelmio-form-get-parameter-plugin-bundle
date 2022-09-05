<?php

use OpenApi\Attributes\OpenApi;
use Sandronimus\NelmioFormGetParameterPluginBundle\Tests\WebTestCase;

class FormGetParameterTest extends WebTestCase
{
    public function testSimpleForm()
    {
        $kernel = static::bootKernel();
        /** @var OpenApi $api */
        $api = $kernel->getContainer()->get('nelmio_api_doc.generator.default')->generate();

        $this->assertEquals('test[choice]', $api->paths[0]->get->parameters[0]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[0]->schema->type);

        $this->assertEquals('test[integer]', $api->paths[0]->get->parameters[1]->name);
        $this->assertEquals('integer', $api->paths[0]->get->parameters[1]->schema->type);

        $this->assertEquals('test[number]', $api->paths[0]->get->parameters[2]->name);
        $this->assertEquals('number', $api->paths[0]->get->parameters[2]->schema->type);

        $this->assertEquals('test[checkbox]', $api->paths[0]->get->parameters[3]->name);
        $this->assertEquals('boolean', $api->paths[0]->get->parameters[3]->schema->type);

        $this->assertEquals('test[date]', $api->paths[0]->get->parameters[4]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[4]->schema->type);

        $this->assertEquals('test[dateTime]', $api->paths[0]->get->parameters[5]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[5]->schema->type);

        $this->assertEquals('test[collection]', $api->paths[0]->get->parameters[6]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[6]->schema->type);

        $this->assertEquals('test[text]', $api->paths[0]->get->parameters[7]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[7]->schema->type);
        
        $this->assertEquals('test2[choice]', $api->paths[0]->get->parameters[8]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[8]->schema->type);

        $this->assertEquals('test2[integer]', $api->paths[0]->get->parameters[9]->name);
        $this->assertEquals('integer', $api->paths[0]->get->parameters[9]->schema->type);

        $this->assertEquals('test2[number]', $api->paths[0]->get->parameters[10]->name);
        $this->assertEquals('number', $api->paths[0]->get->parameters[10]->schema->type);

        $this->assertEquals('test2[checkbox]', $api->paths[0]->get->parameters[11]->name);
        $this->assertEquals('boolean', $api->paths[0]->get->parameters[11]->schema->type);

        $this->assertEquals('test2[date]', $api->paths[0]->get->parameters[12]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[12]->schema->type);

        $this->assertEquals('test2[dateTime]', $api->paths[0]->get->parameters[13]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[13]->schema->type);

        $this->assertEquals('test2[collection]', $api->paths[0]->get->parameters[14]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[14]->schema->type);

        $this->assertEquals('test2[text]', $api->paths[0]->get->parameters[15]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[15]->schema->type);
    }
}
