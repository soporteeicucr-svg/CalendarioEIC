# Sistema-EIC
Sistema de reservas para la escuela de ingeniería civil

# To use AD DS:

## 1. Make sure the path\to\xampp\php directory has the following files
1. libeay32.dll
2. libsasl.dll
3. ssleay32.dll
### Usually, you can find these files in path\to\xampp\sendmail - this library also uses them. But if not, try to search for them inside the xampp directory.

## 2. Uncomment or add the ldap extension in the php.ini (path\to\xampp\php\php.ini) file
extension=ldap

## 3. Restart the server

## 4. Make sure the path\to\xampp\php directory is set in the system environment variable PATH. To know how to do it, see this post.
