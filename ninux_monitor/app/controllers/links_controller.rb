class LinksController < ApplicationController
  # GET /links
  def index
    @links = Dati.group([:ip_sorg, :ip_dest]).count
  end

  # GET /links/:source/:dest
  def show
    @data = Dati.where({ip_sorg: params[:source], ip_dest: params[:destination]})
    logger.debug(@data)
    respond_to do |format|
      format.html  # sjow.html.erb
      format.json do
        json = { labels: [], min: [], max: [], avg: []}
        graph = @data.inject(json) do |acc, d|
          acc[:labels] << d.quando
          acc[:min] << d.min
          acc[:max] << d.max
          acc[:avg] << d.avg

          acc
        end
        render :json => graph
      end
    end
  end
end
