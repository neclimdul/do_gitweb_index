<?php
  // Start an output buffer to be able to set proper headers in the footer.
  ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8"/> 
<meta name="robots" content="index, nofollow"/> 
<title>git.drupalcode.org</title> 
<link rel="stylesheet" type="text/css" href="/gitweb.css"/> 
</head>
<body>
<h1>git://git.drupalcode.org</h1>
<p>Welcome to the ongoing Git mirror of drupal.org CVS repositories. This mirror is built using the <a href="http://github.com/sdboyer/drupalorg-git">scripts</a> that will eventually be used to permanently migrate all of Drupal to Git. The mirror is rebuilt frequently with the latest changes to those scripts (and latest changes in CVS as well), so what's here is always our latest and greatest effort.</p>
<p>If you spot problems, head over to the <a href="http://groups.drupal.org/drupal-org-git-migration-team">migration group</a> or check the <a href="http://drupal.org/project/issues/search?text=&projects=&assigned=&submitted=&participant=&status[]=Open&issue_tags_op=or&issue_tags=git+phase+2">issue queue</a> to see if they've been reported. If not, <a href="http://drupal.org/node/add/project-issue/infrastructure">file an issue</a>, mark it with the 'Git' component, and tag it 'git phase 2'. Or make pull requests against the github repo. Feel free to ping the <a href="http://twitter.com/DrupalGitGremln">@DrupalGitGremln</a>, too!</p>
<p class="warning">This mirror is rebuilt every five minutes, and while it should generally remain consistent from now until migration day, <strong>we make no promises</strong>. Therefore, you should NOT rely on it for serious work.</p>
<p>The <a href="http://git.drupalcode.org/log">build log</a> contains all the output generated during the latest build, and is our primary tool for identifying problems with the migration and figuring out what else needs to be done.</p>
<h2>Projects</h2>
<p>All projects - modules, themes, theme engines, install profiles, and even <a href="http://git.drupalcode.org/project/drupal.git">core</a> itself - now reside in a single directory.</p>
<?php print _list_projects('project', '/var/git/repositories'); ?>
<h2>Sandboxes</h2>
<p>Sandboxes are frozen for now, but are being migrated over and will be dealt with in a later phase of the project.</p>
<?php /* print _list_projects('sandboxes', '/var/git/repositories'); */ ?>
</body>
</html>
<?php
  function _list_projects($dir, $basedir) {
    // We've got a flat file list of projects. This should actually be faster to parse than listing a directory contents.
    $projects = file('/var/git/repositories/projects.list');
    foreach($projects as $i => $line) {
      $project_uri = trim(str_replace(' Drupalcon', '', $line));
      $project_name = basename($project_uri, '.git');
      $project_link = '<a href="/' . $project_uri . '">' . $project_name . '</a>';
      $output .= '<li>' . $project_link . '</li>';
    }
    return '<ul class="project_listing">' . $output . '</ul>';

    // Damien's original file listing based code.
    $list = glob($basedir . '/' . $dir . '/*.git', GLOB_ONLYDIR);
    $output = '';
    foreach ($list as $project_path) {
      $project_uri = trim(substr($project_path, strlen($basedir)), '/');
      $project_name = basename($project_path, '.git');
      $project_link = '<a href="/' . $project_uri . '">' . $project_name . '</a>';
      $output .= '<li>' . $project_link . '</li>';
    }
    return '<ul class="project_listing">' . $output . '</ul>';
  }

  // Serve the page.
  $page = ob_get_clean();
  $etag = md5($page);

  // Output proper headers.
  header('Cache-Control: public, max-age=3600');
  header('Etag: ' . $etag); 

  // Output the page.
  echo $page;

