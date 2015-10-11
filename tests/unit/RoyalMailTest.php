<?php

namespace RoyalMail\tests\unit;

use atoum;

class RoyalMail extends atoum {

  use \RoyalMail\tests\lib\TestDataLoader;

  function testHelperFactory() {
    $this
      ->given($this->newTestedInstance)
      ->object($this->testedInstance->getDataHelper())
      ->isInstanceOf('\RoyalMail\Helper\Data');
  }


  function testConnectorFactory() {
    // Development
    $this
      ->given($this->newTestedInstance)
      ->object($connector = $this->testedInstance->getConnector())
      ->isInstanceOf('\RoyalMail\Connector\soapConnector')
      ->object($connector->getSoapClient())
      ->isInstanceOf('\RoyalMail\Connector\MockSoapClient');
  }


  function testBuildRequest() {
    $this
      ->given($this->newTestedInstance)
      ->array($this->testedInstance->buildRequest('cancelShipment', $this->getRequestParams()))
      ->hasKeys(['cancelShipments', 'integrationHeader'])
      ->hasSize(2);
  }


  function testRequestSending() {
    $this
      ->given($this->newTestedInstance)
      ->array($built = $this->testedInstance->buildRequest('cancelShipment', $this->getRequestParams()))
      ->object($response = $this->testedInstance->send('cancelShipment', $built))
      ->isInstanceOf('\stdClass')
      ->string($response->completedCancelInfo->status->status->statusCode->code)
      ->isEqualTo('Cancelled');
  }



  function getRequestParams() {
    return array_merge(
      $this->getTestSchema('requests/cancelShipment')['valid']['request'],
      ['integrationHeader' => $this->getTestSchema('requests/integrationHeader')['valid']['request']]
    );
  }
}