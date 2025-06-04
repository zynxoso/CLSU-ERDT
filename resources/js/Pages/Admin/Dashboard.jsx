import { CheckCircle, DollarSign, FileText, Users } from "lucide-react";
import { useEffect, useState } from "react";

export default function Dashboard() {
  const [stats, setStats] = useState({
    totalScholars: 0,
    pendingRequests: 0,
    totalDisbursed: 0,
    completionRate: 0,
  });

  const [recentActivity, setRecentActivity] = useState([]);

  useEffect(() => {
    // Fetch dashboard data
    const fetchDashboardData = async () => {
      try {
        const response = await fetch("/api/admin/dashboard");
        const data = await response.json();
        setStats(data.stats);
        setRecentActivity(data.recentActivity);
      } catch (error) {
        console.error("Error fetching dashboard data:", error);
      }
    };

    fetchDashboardData();
  }, []);

  const statCards = [
    {
      name: "Total Scholars",
      value: stats.totalScholars,
      icon: Users,
      change: "+4.75%",
      changeType: "positive",
    },
    {
      name: "Pending Requests",
      value: stats.pendingRequests,
      icon: FileText,
      change: "+12.5%",
      changeType: "negative",
    },
    {
      name: "Total Disbursed",
      value: `₱${stats.totalDisbursed.toLocaleString()}`,
      icon: DollarSign,
      change: "+8.2%",
      changeType: "positive",
    },
    {
      name: "Completion Rate",
      value: `${stats.completionRate}%`,
      icon: CheckCircle,
      change: "+2.3%",
      changeType: "positive",
    },
  ];

  return (
    <div className="space-y-6" data-oid="gn96nk3">
      <div data-oid="6uua52n">
        <h1 className="text-2xl font-semibold text-gray-900" data-oid="6ixn-yx">
          Dashboard
        </h1>
        <p className="mt-1 text-sm text-gray-500" data-oid=".d9o.h1">
          Overview of your scholarship program
        </p>
      </div>

      {/* Stats */}
      <div
        className="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4"
        data-oid="eu4w7fi"
      >
        {statCards.map((stat) => {
          const Icon = stat.icon;
          return (
            <div
              key={stat.name}
              className="relative overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:px-6 sm:py-6"
              data-oid=":n6ojgj"
            >
              <dt data-oid="eh0m9po">
                <div
                  className="absolute rounded-md bg-indigo-500 p-3"
                  data-oid="frm.lc0"
                >
                  <Icon
                    className="h-6 w-6 text-white"
                    aria-hidden="true"
                    data-oid="dh7usui"
                  />
                </div>
                <p
                  className="ml-16 truncate text-sm font-medium text-gray-500"
                  data-oid="12h01r3"
                >
                  {stat.name}
                </p>
              </dt>
              <dd className="ml-16 flex items-baseline" data-oid="b:nevl8">
                <p
                  className="text-2xl font-semibold text-gray-900"
                  data-oid="8083pib"
                >
                  {stat.value}
                </p>
                <p
                  className={`ml-2 flex items-baseline text-sm font-semibold ${
                    stat.changeType === "positive"
                      ? "text-green-600"
                      : "text-red-600"
                  }`}
                  data-oid="mlbmchd"
                >
                  {stat.change}
                </p>
              </dd>
            </div>
          );
        })}
      </div>

      {/* Recent Activity */}
      <div className="bg-white shadow rounded-lg" data-oid="9wqk7--">
        <div className="px-4 py-5 sm:px-6" data-oid="yjofgu:">
          <h3
            className="text-lg font-medium leading-6 text-gray-900"
            data-oid="c058ewk"
          >
            Recent Activity
          </h3>
        </div>
        <div className="border-t border-gray-200" data-oid="qyn3.d1">
          <ul
            role="list"
            className="divide-y divide-gray-200"
            data-oid="p:7n69:"
          >
            {recentActivity.map((activity) => (
              <li
                key={activity.id}
                className="px-4 py-4 sm:px-6"
                data-oid="m:uyyy_"
              >
                <div
                  className="flex items-center justify-between"
                  data-oid="2z8r8wa"
                >
                  <p
                    className="truncate text-sm font-medium text-indigo-600"
                    data-oid="60phpa7"
                  >
                    {activity.description}
                  </p>
                  <div className="ml-2 flex flex-shrink-0" data-oid="66xos6l">
                    <p
                      className="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800"
                      data-oid="1xw.eu1"
                    >
                      {activity.status}
                    </p>
                  </div>
                </div>
                <div
                  className="mt-2 sm:flex sm:justify-between"
                  data-oid="xakp4u4"
                >
                  <div className="sm:flex" data-oid="uujbay3">
                    <p
                      className="flex items-center text-sm text-gray-500"
                      data-oid="-2:a1r0"
                    >
                      {activity.user}
                    </p>
                  </div>
                  <div
                    className="mt-2 flex items-center text-sm text-gray-500 sm:mt-0"
                    data-oid="ja0:qm_"
                  >
                    <p data-oid="ac:1riq">{activity.date}</p>
                  </div>
                </div>
              </li>
            ))}
          </ul>
        </div>
      </div>
    </div>
  );
}
