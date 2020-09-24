<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
<%@ taglib prefix="t" uri="http://tiles.apache.org/tags-tiles" %>
<%@ page session="false" %>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><c:out value="title"/> | Spittr</title>
</head>
<body>
    <header>
        <t:insertAttribute name="header" />
    </header>
    <main>
        <t:insertAttribute name="body"/>
    </main>
    <footer>
        <t:insertAttribute name="footer"/>
    </footer>
</body>
</html>
