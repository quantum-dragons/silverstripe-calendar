<div id="$Name" class="Calendar CalendarTable">
  <div class="body">
    <div class="calendarControls">
      <span class="prev"><a class="eventAction" href="$PrevLink">&laquo; Previous month</a></span>
      <span class="next"><a class="eventAction" href="$NextLink">Next month &raquo;</a></span>
    </div>

    <div class="clear">&nbsp;</div>

    <div class="eventList">
      <% loop $Rows %>
        <h3>$LongMonth $Year</h3>
        <% if $Events %>
          <table>
            <% loop $Events %>
              <tr <% if $InFuture %>class="inFuture"<% end_if %>>
                <td class="date">
                  <% if $StartedBeforeThisMonth %>
                    <span class="continued">&larr;</span>
                  <% else %>
                    <span class="dayOfMonth">$StartDate.DayOfMonth</span>
                    <span class="weekDay">$StartDate.Format(D)</span>
                  <% end_if %>
                </td>
                <td class="description">
                  <h4><a href="$Link?calendardate=$Top.CurrentPageDate">$Title</a></h4>
                  <span class="date">$StartDate.Format('d M Y')<% if $FinishDate %> &mdash; $FinishDate.Format('d M Y')<% end_if %></span><br/>
                  <p>$Content.LimitWordCountXML</p>
                </td>
              </tr>
            <% end_loop %>
          </table>
        <% else %>
          <p class="noEvents">No events registered for this month.</p>
        <% end_if %>
      <% end_loop %>
    </div>

  </div>
  <div class="url" style="display: none;">$Url</div>
</div>