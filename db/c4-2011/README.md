# Castleford City Creator, 2003-2011

The site's first generation, from a 2011 dump of its database (`c4cc_*` tables): the version of
City Creator that Channel 4 commissioned for its Castleford regeneration project, 2003 to 2011.
It has 2,581 users, 9,637 saved cities, 2,333 gallery entries, 404 cards, the FAQ, the polls'
votes and the feedback comments. When the site was rebuilt around 2016, its tables started again
with new rows under the same ids, so these aren't loaded: `../import_db.sh` reads only the files
in `db/` itself.

Two things differ from the dump:

- The users' passwords were stored in plain text, so they're blank here.
- Only the 541 moderated comments are here (37 published, 504 rejected). The other 87,740 were
  the unmoderated queue, nearly all spam with links; the whole dump, those included, is in the
  private iamcal/old-databases repo (`neuron3_mysql_dumps/citycreator_c4.sql`).
