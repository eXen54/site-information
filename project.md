Current project is a website project providing information about the war in Iran, it has frontoffice, that displays the information per article and a backoffice that implements CRUD functionality and article editor.

The frontoffice uses the .htaccess rewriting functionality to display a slug as url by article, ex: http://localhost/site-information/articles/guerre-en-iran-actualite which inside the .htaccess would be generalized as /articles/([a-z0-9]/?$) mapped with frontoffice/detail-article.php?slug=$1.
The frontoffice fetches from the database for the articles and has a dedicated page displaying all articles.
It has a getArticleBySlug function.

The article entity has title, slug, content (main html), meta_content, meta_description etc.

The backoffice in addition to have CRUD functionality has a text editor using tinymce's cdn to generate html from the editor, when creating an article we have the main text editor using tinymce and other filds like title, meta_content, meta_description etc. 
There's a function that transform the title of the article into a slug
ex: Guerre en Iran actualite becomes guerre-en-iran-actualite

