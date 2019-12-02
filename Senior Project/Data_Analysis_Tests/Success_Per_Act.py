#Sucess per amount of acts at shows (Pie)

import sys, functools, csv, plotly.graph_objects as go

def main():

    acts = {}

#Gather data from the CSV file about shows
    with open('../Asheville_Shows.csv') as csvfile:
        readCSV = csv.DictReader(csvfile, delimiter=',')
        for row in readCSV:
            if(row['Act(s)'].rstrip() not in acts and row['Act(s)'] != ''):
                acts[row['Act(s)'].rstrip()] = 0
            try:
                if(row['Band Money?'] == 'Y'):
                    acts[row['Act(s)'].rstrip()] += 1
            except Exception as E:
                print('Got exception {}'.format(E))

    labels = []
    values = []

#Find how many percentages of success per number of acts
    for k in acts.keys():
        if(acts[k] > 1):
            labels.append(k)
            values.append(acts[k])

#Display figure
    fig = go.Figure(data=[go.Pie(labels=labels, values=values)])
    fig.update_layout(title_text = "Percentage of Successful Shows by Number of Acts")
    fig.show()

main()
