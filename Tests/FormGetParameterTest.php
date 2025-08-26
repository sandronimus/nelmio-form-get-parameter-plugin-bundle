<?php

namespace Sandronimus\NelmioFormGetParameterPluginBundle\Tests;

use OpenApi\Attributes\OpenApi;

class FormGetParameterTest extends WebTestCase
{
    public function testOneLevelForm()
    {
        $kernel = static::bootKernel();
        /** @var OpenApi $api */
        $api = $kernel->getContainer()->get('nelmio_api_doc.generator.default')->generate();

        $this->assertEquals('oneLevel[choice]', $api->paths[0]->get->parameters[0]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[0]->schema->type);
        $this->assertEquals(["First", "Second", "Third"], $api->paths[0]->get->parameters[0]->schema->enum);

        $this->assertEquals('oneLevel[multipleChoice][]', $api->paths[0]->get->parameters[1]->name);
        $this->assertEquals('array', $api->paths[0]->get->parameters[1]->schema->type);
        $this->assertEquals('string', $api->paths[0]->get->parameters[1]->schema->items['type']);
        $this->assertEquals(["First", "Second", "Third"], $api->paths[0]->get->parameters[1]->schema->items['enum']);

        $this->assertEquals('oneLevel[integer]', $api->paths[0]->get->parameters[2]->name);
        $this->assertEquals('integer', $api->paths[0]->get->parameters[2]->schema->type);

        $this->assertEquals('oneLevel[number]', $api->paths[0]->get->parameters[3]->name);
        $this->assertEquals('number', $api->paths[0]->get->parameters[3]->schema->type);

        $this->assertEquals('oneLevel[checkbox]', $api->paths[0]->get->parameters[4]->name);
        $this->assertEquals('boolean', $api->paths[0]->get->parameters[4]->schema->type);

        $this->assertEquals('oneLevel[date]', $api->paths[0]->get->parameters[5]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[5]->schema->type);
        $this->assertEquals('date', $api->paths[0]->get->parameters[5]->schema->format);

        $this->assertEquals('oneLevel[dateTime]', $api->paths[0]->get->parameters[6]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[6]->schema->type);
        $this->assertEquals('date-time', $api->paths[0]->get->parameters[6]->schema->format);

        $this->assertEquals('oneLevel[collection][]', $api->paths[0]->get->parameters[7]->name);
        $this->assertEquals('array', $api->paths[0]->get->parameters[7]->schema->type);
        $this->assertEquals('integer', $api->paths[0]->get->parameters[7]->schema->items['type']);
        $this->assertEquals('true', $api->paths[0]->get->parameters[7]->schema->items['required']);

        $this->assertEquals('oneLevel[text]', $api->paths[0]->get->parameters[8]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[8]->schema->type);

        $this->assertEquals('oneLevel[fieldWithSummaryDoc]', $api->paths[0]->get->parameters[9]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[9]->schema->type);
        $this->assertEquals('Text field with documentation in summary', $api->paths[0]->get->parameters[9]->description);

        $this->assertEquals('oneLevel[fieldWithVarDoc]', $api->paths[0]->get->parameters[10]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[10]->schema->type);
        $this->assertEquals('Text field with variable documentation', $api->paths[0]->get->parameters[10]->description);

        $this->assertEquals('oneLevel[fieldWithFormTypeDoc]', $api->paths[0]->get->parameters[11]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[11]->schema->type);
        $this->assertEquals('Text field with form type documentation', $api->paths[0]->get->parameters[11]->description);
        $this->assertEquals('Example string', $api->paths[0]->get->parameters[11]->example);
    }

    public function testTwoLevelLevelForm()
    {
        $kernel = static::bootKernel();
        /** @var OpenApi $api */
        $api = $kernel->getContainer()->get('nelmio_api_doc.generator.default')->generate();

        $this->assertEquals('twoLevel[text]', $api->paths[0]->get->parameters[12]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[12]->schema->type);

        $this->assertEquals('twoLevel[oneLevelModel][choice]', $api->paths[0]->get->parameters[13]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[13]->schema->type);
        $this->assertEquals(["First", "Second", "Third"], $api->paths[0]->get->parameters[13]->schema->enum);

        $this->assertEquals('twoLevel[oneLevelModel][multipleChoice][]', $api->paths[0]->get->parameters[14]->name);
        $this->assertEquals('array', $api->paths[0]->get->parameters[14]->schema->type);
        $this->assertEquals('string', $api->paths[0]->get->parameters[14]->schema->items['type']);
        $this->assertEquals(["First", "Second", "Third"], $api->paths[0]->get->parameters[14]->schema->items['enum']);

        $this->assertEquals('twoLevel[oneLevelModel][integer]', $api->paths[0]->get->parameters[15]->name);
        $this->assertEquals('integer', $api->paths[0]->get->parameters[15]->schema->type);

        $this->assertEquals('twoLevel[oneLevelModel][number]', $api->paths[0]->get->parameters[16]->name);
        $this->assertEquals('number', $api->paths[0]->get->parameters[16]->schema->type);

        $this->assertEquals('twoLevel[oneLevelModel][checkbox]', $api->paths[0]->get->parameters[17]->name);
        $this->assertEquals('boolean', $api->paths[0]->get->parameters[17]->schema->type);

        $this->assertEquals('twoLevel[oneLevelModel][date]', $api->paths[0]->get->parameters[18]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[18]->schema->type);
        $this->assertEquals('date', $api->paths[0]->get->parameters[18]->schema->format);

        $this->assertEquals('twoLevel[oneLevelModel][dateTime]', $api->paths[0]->get->parameters[19]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[19]->schema->type);
        $this->assertEquals('date-time', $api->paths[0]->get->parameters[19]->schema->format);

        $this->assertEquals('twoLevel[oneLevelModel][collection][]', $api->paths[0]->get->parameters[20]->name);
        $this->assertEquals('array', $api->paths[0]->get->parameters[20]->schema->type);
        $this->assertEquals('integer', $api->paths[0]->get->parameters[20]->schema->items['type']);
        $this->assertEquals('true', $api->paths[0]->get->parameters[20]->schema->items['required']);

        $this->assertEquals('twoLevel[oneLevelModel][text]', $api->paths[0]->get->parameters[21]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[21]->schema->type);

        $this->assertEquals('twoLevel[oneLevelModel][fieldWithSummaryDoc]', $api->paths[0]->get->parameters[22]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[22]->schema->type);
        $this->assertEquals('Text field with documentation in summary', $api->paths[0]->get->parameters[22]->description);

        $this->assertEquals('twoLevel[oneLevelModel][fieldWithVarDoc]', $api->paths[0]->get->parameters[23]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[23]->schema->type);
        $this->assertEquals('Text field with variable documentation', $api->paths[0]->get->parameters[23]->description);

        $this->assertEquals('twoLevel[oneLevelModel][fieldWithFormTypeDoc]', $api->paths[0]->get->parameters[24]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[24]->schema->type);
        $this->assertEquals('Text field with form type documentation', $api->paths[0]->get->parameters[24]->description);
        $this->assertEquals('Example string', $api->paths[0]->get->parameters[24]->example);
    }

    public function testThreeLevelLevelForm()
    {
        $kernel = static::bootKernel();
        /** @var OpenApi $api */
        $api = $kernel->getContainer()->get('nelmio_api_doc.generator.default')->generate();

        $this->assertEquals('threeLevel[text]', $api->paths[0]->get->parameters[25]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[25]->schema->type);

        $this->assertEquals('threeLevel[twoLevelModel][text]', $api->paths[0]->get->parameters[26]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[26]->schema->type);

        $this->assertEquals('threeLevel[twoLevelModel][oneLevelModel][choice]', $api->paths[0]->get->parameters[27]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[27]->schema->type);
        $this->assertEquals(["First", "Second", "Third"], $api->paths[0]->get->parameters[27]->schema->enum);

        $this->assertEquals('threeLevel[twoLevelModel][oneLevelModel][multipleChoice][]', $api->paths[0]->get->parameters[28]->name);
        $this->assertEquals('array', $api->paths[0]->get->parameters[28]->schema->type);
        $this->assertEquals('string', $api->paths[0]->get->parameters[28]->schema->items['type']);
        $this->assertEquals(["First", "Second", "Third"], $api->paths[0]->get->parameters[28]->schema->items['enum']);

        $this->assertEquals('threeLevel[twoLevelModel][oneLevelModel][integer]', $api->paths[0]->get->parameters[29]->name);
        $this->assertEquals('integer', $api->paths[0]->get->parameters[29]->schema->type);

        $this->assertEquals('threeLevel[twoLevelModel][oneLevelModel][number]', $api->paths[0]->get->parameters[30]->name);
        $this->assertEquals('number', $api->paths[0]->get->parameters[30]->schema->type);

        $this->assertEquals('threeLevel[twoLevelModel][oneLevelModel][checkbox]', $api->paths[0]->get->parameters[31]->name);
        $this->assertEquals('boolean', $api->paths[0]->get->parameters[31]->schema->type);

        $this->assertEquals('threeLevel[twoLevelModel][oneLevelModel][date]', $api->paths[0]->get->parameters[32]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[32]->schema->type);
        $this->assertEquals('date', $api->paths[0]->get->parameters[32]->schema->format);

        $this->assertEquals('threeLevel[twoLevelModel][oneLevelModel][dateTime]', $api->paths[0]->get->parameters[33]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[33]->schema->type);
        $this->assertEquals('date-time', $api->paths[0]->get->parameters[33]->schema->format);

        $this->assertEquals('threeLevel[twoLevelModel][oneLevelModel][collection][]', $api->paths[0]->get->parameters[34]->name);
        $this->assertEquals('array', $api->paths[0]->get->parameters[34]->schema->type);
        $this->assertEquals('integer', $api->paths[0]->get->parameters[34]->schema->items['type']);
        $this->assertEquals('true', $api->paths[0]->get->parameters[34]->schema->items['required']);

        $this->assertEquals('threeLevel[twoLevelModel][oneLevelModel][text]', $api->paths[0]->get->parameters[35]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[35]->schema->type);

        $this->assertEquals('threeLevel[twoLevelModel][oneLevelModel][fieldWithSummaryDoc]', $api->paths[0]->get->parameters[36]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[36]->schema->type);
        $this->assertEquals('Text field with documentation in summary', $api->paths[0]->get->parameters[36]->description);

        $this->assertEquals('threeLevel[twoLevelModel][oneLevelModel][fieldWithVarDoc]', $api->paths[0]->get->parameters[37]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[37]->schema->type);
        $this->assertEquals('Text field with variable documentation', $api->paths[0]->get->parameters[37]->description);

        $this->assertEquals('threeLevel[twoLevelModel][oneLevelModel][fieldWithFormTypeDoc]', $api->paths[0]->get->parameters[38]->name);
        $this->assertEquals('string', $api->paths[0]->get->parameters[38]->schema->type);
        $this->assertEquals('Text field with form type documentation', $api->paths[0]->get->parameters[38]->description);
        $this->assertEquals('Example string', $api->paths[0]->get->parameters[38]->example);
    }
}
