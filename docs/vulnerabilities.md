# vulnerabilities

1. SQL Injection

I executed the attach by inserting the following input in both username and comment fields:

```
' OR 1=1; --
```

Effect on the database query:

If the comment field contains test' OR 1=1; --, the SQL query becomes:

```
INSERT INTO comments (username, comment_text)
VALUES ('test' OR 1=1; --', 'test' OR 1=1; --')
```

The -- starts a comment in SQL, so everything after it is ignored.

Impact:

Unauthorized data access or manipulation.

<hr>
2. XSS:

I injected the following script into the comment field:

```
<script>alert('XSS');</script>
```

Observed behavior:

When the page reloaded, the alert box with the message XSS appeared immediately.

This demonstrates stored XSS: the malicious script is stored in the database and executed whenever anyone views the page.

<hr>
3. CSRF

Scenario

- Victim authentication

Suppose a user is logged into your comment app.

Their browser has an active session with valid cookies, so any request to index.php is automatically authenticated.

Attacker preparation: A malicious actor creates a separate website with an HTML form designed to submit a comment to your app.

Example:

```
<form method="POST" action="http://localhost/vulnerable-app/index.php">
  <input type="hidden" name="username" value="attacker">
  <input type="hidden" name="comment_text" value="CSRF attack!">
</form>
<script>
  document.forms[0].submit();
</script>
```

notice that the attackeer can reatch the form action through victim actual domain 'in my case localhost'

This form is invisible to the user and automatically submits itself via the <script>.

Execution of the attack

The victim visits the attacker’s website while still logged into your comment app.

The browser automatically includes the session cookies when submitting the form to your app.

The malicious POST request adds a comment to the database without the victim’s knowledge or consent.

Impact

Unauthorized actions are performed in the victim’s context.

Data integrity is compromised — comments or other operations can be submitted as the victim.

Users may be tricked into performing destructive actions on their own account or in your app.

<hr>
