select 
 c.id, c.title, c.description, c.alias, c.params
 from joomla.ja_contentitem_tag_map m
join joomla.ja_categories c on m.content_item_id=c.id
where m.type_alias='com_content.category'
and m.tag_id=2
order by c.note
