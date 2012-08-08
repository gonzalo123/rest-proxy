<?php
use RestProxy\RestProxy;
class TestRestProxy extends \PHPUnit_Framework_TestCase
{
    function testValidRequest()
    {
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $request->expects($this->any())->method('getPathInfo')->will($this->returnValue('/github/users/gonzalo123'));
        $request->expects($this->any())->method('getQueryString')->will($this->returnValue(''));
        $request->expects($this->any())->method('getMethod')->will($this->returnValue('GET'));

        $curl = $this->getMock('RestProxy\CurlWrapper');
        $curl->expects($this->any())->method('doGet')->will(
            $this->returnValue(
                '{"followers":40,"html_url":"https://github.com/gonzalo123","type":"User","company":null,"public_repos":51,"following":1,"blog":"http://gonzalo123.wordpress.com/","location":null,"avatar_url":"https://secure.gravatar.com/avatar/6aa6fe484173856751a24135b4dd4586?d=https://a248.e.akamai.net/assets.github.com%2Fimages%2Fgravatars%2Fgravatar-140.png","gravatar_id":"6aa6fe484173856751a24135b4dd4586","public_gists":7,"login":"gonzalo123","name":"Gonzalo Ayuso","email":null,"hireable":false,"url":"https://api.github.com/users/gonzalo123","created_at":"2008-12-08T14:17:03Z","id":39072,"bio":null}'
            )
        );

        $proxy = new RestProxy($request, $curl);
        $proxy->register('github', 'https://api.github.com');
        $proxy->run();
        $output = json_decode($proxy->getContent(), TRUE);
        $this->assertEquals(40, $output['followers']);
        $this->assertEquals('http://gonzalo123.wordpress.com/', $output['blog']);
    }

    function testWrongMethodRequest()
    {
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $request->expects($this->any())->method('getPathInfo')->will($this->returnValue('/github/users/gonzalo123'));
        $request->expects($this->any())->method('getQueryString')->will($this->returnValue(''));
        $request->expects($this->any())->method('getMethod')->will($this->returnValue('POST'));

        $curl = $this->getMock('RestProxy\CurlWrapper');
        $curl->expects($this->any())->method('doGet')->will(
            $this->returnValue(
                '{"followers":40,"html_url":"https://github.com/gonzalo123","type":"User","company":null,"public_repos":51,"following":1,"blog":"http://gonzalo123.wordpress.com/","location":null,"avatar_url":"https://secure.gravatar.com/avatar/6aa6fe484173856751a24135b4dd4586?d=https://a248.e.akamai.net/assets.github.com%2Fimages%2Fgravatars%2Fgravatar-140.png","gravatar_id":"6aa6fe484173856751a24135b4dd4586","public_gists":7,"login":"gonzalo123","name":"Gonzalo Ayuso","email":null,"hireable":false,"url":"https://api.github.com/users/gonzalo123","created_at":"2008-12-08T14:17:03Z","id":39072,"bio":null}'
            )
        );

        $proxy = new RestProxy($request, $curl);
        $proxy->register('github', 'https://api.github.com');
        $proxy->run();
        $output = json_decode($proxy->getContent(), TRUE);
        $this->assertNotEquals(40, $output['followers']);
        $this->assertNotEquals('http://gonzalo123.wordpress.com/', $output['blog']);
    }
}
