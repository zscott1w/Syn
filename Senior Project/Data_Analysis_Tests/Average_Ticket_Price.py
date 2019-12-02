#Average Ticket Price per venue (Bar)
import sys, functools, csv, plotly.graph_objects as go

def main():

    price = {}
    count = {}
    attend = {}

#Gather data from the CSV file about shows
    with open('../Asheville_Shows.csv') as csvfile:
        readCSV = csv.DictReader(csvfile, delimiter=',')
        for row in readCSV:
            #print(row['Venue'], row['Ticket Price'])
            if(row['Venue'].rstrip() not in price):
                 price[row['Venue'].rstrip()] = 0
                 count[row['Venue'].rstrip()] = 0
                 attend[row['Venue'].rstrip()] = 0
            try:
                price[row['Venue'].rstrip()] += float(row['Ticket Price'])
                count[row['Venue'].rstrip()] += 1
                if(row['Attendence?'] == 'Y'):
                    attend[row['Venue'].rstrip()] += 1
            except Exception as E:
                print('Got exception {}'.format(E))
    x = []
    y = []
    colors = ['orange'] * 11
    i = 0

#Calculate average price for tickets at the venues
    for k in price.keys():
        if(count[k] > 1 and price[k]/count[k] > 0):
            print(k, price[k]/count[k], attend[k]/count[k])
            x.append(k)
            y.append(price[k]/count[k])
        if(count[k] > 1 and attend[k] / count[k] < 0.25):
            colors[i] = 'crimson'
        elif(count[k] > 1 and attend[k] / count[k] > 0.75):
            colors[i] = 'green'
        if(count[k] > 1):
            i += 1


#Display graph with given values
    graph = go.Figure([go.Bar(x=x, y=y, marker_color = colors)])
    graph.update_layout(title=go.layout.Title(text = 'Average Ticket Price per Venue'), xaxis = go.layout.XAxis(title=go.layout.xaxis.Title(text = 'Venue')),
                        yaxis = go.layout.YAxis(title=go.layout.yaxis.Title(text = 'Average Ticket Price (in dollars)')))
    graph.show()

main()
