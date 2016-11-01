<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('create a user profile');
$I->haveHttpHeader('Content-Type', 'application/json');
$I->sendPOST('/user', ['name' => 'Javier Neyra', 'address' => 'some address']);
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED); // 201
$I->seeResponseIsJson();
$I->seeResponseContains('"profile_id"');

$createdProfileId = $I->grabDataFromResponseByJsonPath("$.profile_id")[0];
$resourceUrl = $I->grabHttpHeader('Location');

$I->wantTo('get the user profile i just created');
$I->sendGET($resourceUrl);
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
$I->seeResponseIsJson();
$I->seeResponseContainsJson([
    'id' => $createdProfileId,
    'name' => 'Javier Neyra',
    'address' => 'some address'
]);

$I->wantTo('edit the user profile');
$I->sendPOST(
    $resourceUrl,
    ['id' => $createdProfileId, 'name' => 'edited user name', 'address' => 'some edited address']
);
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
$I->seeResponseIsJson();
$I->seeResponseContains('"profile_id"');
$editedProfileId = $I->grabDataFromResponseByJsonPath("$.profile_id")[0];

$I->assertEquals($editedProfileId, $createdProfileId);

$I->wantTo('get the edited user profile');
$I->sendGET($resourceUrl);
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
$I->seeResponseIsJson();
$I->seeResponseContainsJson([
    'id' => $createdProfileId,
    'name' => 'edited user name',
    'address' => 'some edited address'
]);

$I->wantTo("Delete the user profile");
$I->sendDELETE($resourceUrl);
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
$I->seeResponseIsJson();
$I->seeResponseContainsJson([
    'msg' => "Operation success"
]);