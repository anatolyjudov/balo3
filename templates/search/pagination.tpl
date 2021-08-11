<table class="nav" cellspacing=1 align=center width=95%>
<tr>
<td class="prev">{if $shown_rows_from>1}<a href="./?{if $shown_rows_prev_skip > 0}skip={$shown_rows_prev_skip}{if $search neq ''}&{/if}{/if}{if $search neq ''}search={$search}{/if}{if $selected_area neq ''}&{/if}{if $selected_area neq ''}{$selected_area}=on{/if}"><<<<</a>{/if}</td>
<td class="here">[{$shown_rows_from}-{$shown_rows_to}] {#lang_search_pagination_from#} {$results_count}</td>
<td class="next">{if $shown_rows_to < $results_count}<a href="./?skip={$shown_rows_next_skip}{if $search neq ''}&search={$search}{/if}{if $selected_area neq ''}&{$selected_area}=on{/if}">>>>></a>{/if}</td>
</tr>
</table>